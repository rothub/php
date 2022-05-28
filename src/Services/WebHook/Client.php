<?php

namespace RotHub\PHP\Services\WebHook;

use RotHub\PHP\Exceptions\Error;
use RotHub\PHP\Providers\RequestProvider;
use Symfony\Component\HttpFoundation\Request;

class Client extends \RotHub\PHP\Services\AbstractService
{
    const FROM_GITEE = 'gitee';
    const FROM_GITHUB = 'github';

    /**
     * @var Request 请求.
     */
    protected $request;
    /**
     * @var string 来源.
     */
    protected $from;
    /**
     * @var array 仓库.
     */
    protected $repository;

    /**
     * @inheritdoc
     */
    protected function init(): void
    {
        foreach ($this->config as $key => &$value) {
            if (!($value['agent'] ?? '')) {
                if ($key === static::FROM_GITHUB) {
                    $value['agent'] = 'GitHub-Hookshot';
                } else if ($key === static::FROM_GITEE) {
                    $value['agent'] = 'git-oschina-hook';
                }
            }
        }

        $request = $this->app[RequestProvider::name()];

        $this->setRequest($request);
    }

    /**
     * 设置请求.
     *
     * @param Request $request 请求.
     * @return static
     */
    public function setRequest(Request $request): static
    {
        $this->request = $request;

        $this->setFrom();
        $this->setRepository();

        return $this;
    }

    /**
     * 验证签名.
     *
     * @return bool
     */
    public function checkSign(): bool
    {
        $secret = $this->take('secret', '');

        if ($secret) {
            $method = 'check' . ucfirst($this->from);

            if (method_exists($this, $method)) {
                return $this->$method($this->request, $secret);
            }
        }

        return true;
    }

    /**
     * 验证仓库.
     *
     * @return bool
     */
    public function checkRepository(): bool
    {
        return $this->repository ? true : false;
    }

    /**
     * 执行.
     *
     * @return array
     */
    public function exec(): array
    {
        $dir = $this->repository['dir'] ?? '';
        $branch = $this->take('branch', 'main');

        $cmd = 'cd ' . $dir;
        $cmd .= ' && git fetch --all';
        $cmd .= ' && git reset --hard origin/' . $branch;
        exec($cmd . ' 2>&1', $output, $status);

        return [
            'cmd' => $cmd,
            'output' => $output,
            'status' => $status,
        ];
    }

    /**
     * 写日志.
     *
     * @param mixed $data 数据.
     * @return void
     */
    public function log(mixed $data): void
    {
        $filename = $this->take('log', './webhook.log');

        $content = $this->bracket(date('Y-m-d H:i:s'));
        $content .= $this->bracket($this->request->server->get('REMOTE_ADDR', ''));
        $content .= $this->bracket($this->from ?? '');
        $content .= $this->bracket($this->repository['repository'] ?? '');
        $content .= ' ' . trim(json_encode($data), '"') . PHP_EOL;

        file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
    }

    /**
     * 运行.
     *
     * @return bool
     */
    public function run(): bool
    {
        try {
            $this->checkSign() or $this->error('invalid signature.');
            $this->checkRepository() or $this->error('invalid repository.');
            $res = $this->exec();

            if ($res['status'] === 0) {
                $this->log('success.');
            } else {
                $this->log(print_r($res, true));
            }

            return $res['status'] === 0;
        } catch (\Throwable $th) {
            $this->log($th->getMessage());
        }

        return false;
    }

    /**
     * 取配置.
     *
     * @param string $name 名称.
     * @param mixed $default 默认值.
     * @return mixed
     */
    protected function take(string $name, mixed $default = null): mixed
    {
        $value1 = $this->repository[$name] ?? $default;
        $value2 = $this->config[$this->from][$name] ?? $default;

        return $value1 ? $value1 : $value2;
    }

    /**
     * 设置来源.
     *
     * @return void
     */
    protected function setFrom(): void
    {
        $agent = $this->request->headers->get('User-Agent', '');

        foreach ($this->config as $key => $value) {
            if (strstr($agent, $value['agent'])) {
                $this->from = $key;

                break;
            }
        }
    }

    /**
     * 设置仓库.
     *
     * @return void
     */
    protected function setRepository(): void
    {
        $data = json_decode($this->request->getContent(), true);
        $repository = $data['repository']['full_name'] ?? '';

        $repositories = $this->config[$this->from]['repositories'] ?? [];

        foreach ($repositories as $item) {
            if ($item['repository'] === $repository) {
                $this->repository = $item;

                break;
            }
        }
    }

    /**
     * 验证 GitHub.
     *
     * @param Request $request 请求.
     * @param string $str 字符串.
     * @return bool
     */
    protected function checkGithub(Request $request, string $secret): bool
    {
        $sign = $request->headers->get('X-Hub-Signature');

        $data = $request->getContent();
        $hash = 'sha1=' . hash_hmac('sha1', $data,  $secret);

        return $sign === $hash;
    }

    /**
     * 验证 Gitee.
     *
     * @param Request $request 请求.
     * @param string $str 字符串.
     * @return bool
     */
    protected function checkGitee(Request $request, string $secret): bool
    {
        $token = $request->headers->get('X-Gitee-Token');

        $time = $request->headers->get('X-Gitee-Timestamp');
        $data = $time . "\n" . $secret;
        $hash = hash_hmac('sha256', $data,  $secret, true);
        $sign = base64_encode($hash);

        return $token === $sign;
    }

    /**
     * 加括号.
     *
     * @param string $str 字符串.
     * @return string
     */
    protected function bracket(string $str): string
    {
        return '[' . $str . ']';
    }

    /**
     * 异常.
     *
     * @param string $str 字符串.
     * @return void
     */
    protected function error(string $str): void
    {
        Error::fail($str);
    }
}
