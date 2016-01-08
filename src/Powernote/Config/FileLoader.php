<?php
namespace Powernote\Config;

use Powernote\Filesystem\Filesystem;

/**
 * 配置文件加载器
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class FileLoader implements LoaderInterface
{

    /**
     * 文件操作类实例
     *
     * @var \Powernote\Filesystem\Filesystem
     */
    protected $files;

    /**
     * 默认的配置文件路径
     *
     * @var string
     */
    protected $defaultPath;

    /**
     * 配置文件路径列表
     *
     * @var array
     */
    protected $paths = [];

    /**
     * 创建一个新的文件配置加载器
     *
     * @param Filesystem $files
     * @param string $defaultPath
     * @return void
     */
    public function __construct(Filesystem $files, $defaultPath)
    {
        $this->files = $files;
        $this->defaultPath = $defaultPath;
    }

    /**
     * 加载指定的配置文件
     *
     * @param string $environment 环境
     * @param string $group 组
     * @param string $namespace
     * @return array
     */
    public function load($environment, $group, $namespace = null)
    {
        $items = [];
        
        $path = $this->getPath($namespace);
        
        if (is_null($path)) return $items;
        
        // 总是加载默认的配置
        $file = "{$path}/{$group}.php";
        
        if ($this->files->exists($file))
        {
            $items = $this->files->getRequire($file);
        }
        
        // 加载指定环境中的配置
        $file = "{$path}/{$environment}/{$group}.php";
        if ($this->files->exists($file))
        {
            $items = array_merge($items, $this->files->getRequire($file));
        }
        
        return $items;
    }

    /**
     * 指定的配置文件是否存在
     *
     * @param string $group
     * @param string $namespace
     * @return bool
     */
    public function exists($group, $namespace = null)
    {
        $path = $this->getPath($namespace);
        $file = "{$path}/{$group}.php";
        return $this->files->exists($file);
    }

    /**
     * 根据空间名获取路径
     *
     * @param string $namespace
     * @return string
     */
    public function getPath($namespace)
    {
        if (is_null($namespace)) return $this->defaultPath;
        
        if (isset($this->paths[$namespace])) return $this->paths[$namespace];
    }

    /**
     * 添加路径
     *
     * @param string $namespace
     * @param string $path
     * @return void
     */
    public function addPath($namespace, $path)
    {
        $this->paths[$namespace] = $path;
    }

    /**
     * 获取路径列表
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }
}