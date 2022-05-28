<?php

namespace RotHub\PHP\Generate;

use RotHub\PHP\Client;
use RotHub\PHP\Services\AbstractService;

class Facade
{
    /**
     * @var string 命名空间.
     */
    protected $namespace;
    /**
     * @var string 路径.
     */
    protected $path;

    /**
     * 设置命名空间.
     *
     * @param string $value 值.
     * @return static
     */
    public function setNameSpace(string $value): static
    {
        $this->namespace = $value;

        return $this;
    }

    /**
     * 设置路径.
     *
     * @param string $value 值.
     * @return static
     */
    public function setPath(string $value): static
    {
        $this->path = $value;

        return $this;
    }

    /**
     * 清空.
     *
     * @return static
     */
    public function clear(): static
    {
        $files = glob($this->path . '*?.php');

        foreach ($files as $file) {
            $name = $this->filename($file);
            $class = '\\' . $this->namespace . '\\' . $name;

            $ref = new \ReflectionClass($class);
            $ref->isAbstract() or unlink($file);
        }

        return $this;
    }

    /**
     * 运行.
     *
     * @return void
     */
    public function run(): void
    {
        $client = new Client();
        $keys = $client->keys();

        foreach ($keys as $key) {
            $content = $this->class($key, $client[$key]);
            $this->save($key, $content);
        }
    }

    /**
     * 类解析.
     *
     * @param string $key 名称.
     * @param object $object 对象.
     * @return string
     */
    protected function class(string $key, object $object): string
    {
        $res[] = '<?php';
        $res[] = '';
        $res[] = 'namespace ' . $this->namespace . ';';
        $res[] = '';
        $res[] = '/**';

        $ref = new \ReflectionObject($object);
        $methods = $this->method($ref);
        array_push($res, ...$methods);

        $res[] = ' *';
        $res[] = ' * @see \\' . $ref->getName();
        $res[] = ' */';
        $res[] = 'class ' . $key . ' extends \RotHub\PHP\Facades\AbstractFacade';
        $res[] = '{';
        $res[] = '    /**';
        $res[] = '     * @inheritdoc';
        $res[] = '     */';
        $res[] = '    protected $class = \RotHub\PHP\Providers\\' . $key . 'Provider::class;';
        $res[] = '}';
        $res[] = '';

        return join(PHP_EOL, $res);
    }

    /**
     * 方法解析.
     *
     * @param \ReflectionObject $ref 实例.
     * @return array
     */
    protected function method(\ReflectionObject $ref): array
    {
        $res = [];
        $filter = $this->filter();
        $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if (!in_array($method->name, $filter)) {
                $str = ' * @method';

                if ($method->isStatic()) {
                    $str .= ' static';
                }

                if ($method->hasReturnType()) {
                    $str .= ' ' . $method->getReturnType();
                }

                $str .= ' ' . $method->getName() . '(';

                $parameters = $method->getParameters();
                foreach ($parameters as $key => $parameter) {
                    $key === 0 or $str .= ', ';

                    if ($parameter->hasType()) {
                        $str .= $parameter->getType() . ' ';
                    }

                    if ($parameter->isPassedByReference()) {
                        $str .= '&';
                    }

                    $str .= '$' . $parameter->getName();

                    if ($parameter->isDefaultValueAvailable()) {
                        $value = $parameter->getDefaultValue();
                        $str .= ' = ' . json_encode($value);
                    }
                }

                $str .= ')';

                $res[] = $str;
            }
        }

        return $res;
    }

    /**
     * 保存.
     *
     * @param string $key 名称.
     * @param string $content 内容.
     * @return void
     */
    protected function save(string $key, string $content): void
    {
        $filename = $this->path . $key . '.php';

        file_put_contents($filename, $content);
    }

    /**
     * 过滤.
     *
     * @return array
     */
    protected function filter(): array
    {
        $methods = (new \ReflectionClass(AbstractService::class))
            ->getMethods(\ReflectionMethod::IS_PUBLIC);

        return array_map(function ($method) {
            return $method->name;
        }, $methods);
    }

    /**
     * 文件名.
     *
     * @return string
     */
    protected function filename(string $path): string
    {
        $res = pathinfo($path);

        return ucfirst($res['filename']);
    }
}
