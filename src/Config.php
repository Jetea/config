<?php

namespace Jetea\Config;

/**
 * 框架配置辅助类
 *
 * @example
 * print_r($this->ctx->Ctx->getConf('upload.ip@common/main'));
 * $this->ctx->Ctx->setConf('upload.ip@common/main', '123');
 * print_r($this->ctx->Ctx->getConf('upload.ip@common/main'));
 * var_dump($this->ctx->Ctx->getSConf('default.master.host@db'));
 */
class Config
{
    /**
     * All of the loaded configuration files.
     *
     * @var array
     */
    protected $loadedConfigurations = [];

    /**
     * config 目录
     */
    private $configDir;

    /**
     * 配置的数据仓库
     */
    private $repository;

    /**
     * Config constructor.
     *
     * @param $configDir string 配置文件夹
     */
    public function __construct($configDir)
    {
        $this->configDir = realpath($configDir);
        $this->repository = new Repository;
    }

    /**
     * 获取配置
     *
     * @param $item string 配置选项,如 'upload.ip@common/main'
     * @param mixed $default 默认值
     *
     * @return mixed
     *
     * @example
     * $this->getConfig('upload.ip@common/main');
     */
    public function get($item, $default = null)
    {
        $segments = explode('.', $item, 2);
        $this->load($segments[0]);

        return $this->repository->get($item, $default);
    }

    /**
     * 动态修改配置
     *
     * @param $name string 如 'main',
     * @return void
     */
    protected function load($name)
    {
        if (isset($this->loadedConfigurations[$name])) {
            return;
        }

        $this->loadedConfigurations[$name] = true;

        $file = $this->configDir . '/' . $name . '.php';
        $this->repository->set($name, require $file);
    }

    /**
     * 动态修改配置
     *
     * @param $item string 如 'upload.ip@Ctx/main',
     * @param $config mixed 配置值
     * @return void
     */
    public function set($item, $config)
    {
        $segments = explode('.', $item, 2);
        $this->load($segments[0]);

        $this->repository->set($item, $config);
    }
}
