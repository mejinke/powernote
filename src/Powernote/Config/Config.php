<?php
namespace Powernote\Config;

use \Arrayaccess;

/**
 * 配置文件加载管理器
 *
 * @author tian <mejinke@gmail.com> Jan 7, 2016 5:03:41 PM
 *
 */
class Config implements ArrayAccess
{

    /**
     * 配置文件加载器
     *
     * @var \Powernote\Config\LoaderInterface
     */
    protected $loader;

    /**
     * 当前运行环境
     *
     * @var string
     */
    protected $environment;

    /**
     * 初始化
     *
     * @param LoaderInterface $loader
     * @param string $environment
     */
    public function __construct(LoaderInterface $loader, $environment)
    {
        $this->loader = $loader;
        $this->environment = $environment;
    }

    public function get($key, $default = null)
    {
        return $this->loader->load($this->environment, $key);
    }

    /**
     *
     * @param offset
     */
    public function offsetExists($offset)
    {}

    /**
     *
     * @param offset
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     *
     * @param offset
     * @param value
     */
    public function offsetSet($offset, $value)
    {}

    /**
     *
     * @param offset
     */
    public function offsetUnset($offset)
    {}
}