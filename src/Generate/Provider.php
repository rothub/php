<?php

namespace RotHub\PHP\Generate;

class Provider
{
    /**
     * @var string 命名空间.
     */
    protected $namespace;
    /**
     * @var string 名称.
     */
    protected $name;
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
     * 设置名称.
     *
     * @param string $value 值.
     * @return static
     */
    public function setName(string $value): static
    {
        $this->name = $value;

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
     * 运行.
     *
     * @return void
     */
    public function run(): void
    {
        $files = glob($this->path . '/*?Provider.php');

        $rows[] = '<?php';
        $rows[] = '';
        $rows[] = 'namespace ' . $this->namespace . ';';
        $rows[] = '';
        $rows[] = 'trait ' . $this->filename($this->name);
        $rows[] = '{';
        $rows[] = '    /**';
        $rows[] = '     * @var array 服务提供者.';
        $rows[] = '     */';
        $rows[] = '    protected $providers = [';

        foreach ($files as $file) {
            $name = $this->filename($file);
            $class = '\\' . $this->namespace . '\\' . $name;

            $ref = new \ReflectionClass($class);
            if (!($ref->isAbstract() || $ref->isTrait())) {
                $rows[] = '        \\' . $this->namespace . '\\' . $name . '::class,';
            }
        }

        $rows[] = '    ];';
        $rows[] = '}';
        $rows[] = '';

        file_put_contents($this->name, join(PHP_EOL, $rows));
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
