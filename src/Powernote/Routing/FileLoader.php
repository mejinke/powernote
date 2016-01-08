<?php
namespace Powernote\Routing;

use Powernote\Filesystem\Filesystem;

/**
 * 路由配置文件加载器
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class FileLoader implements LoaderInterface
{

    /**
     * Filesystem
     *
     * @var \Powernote\Filesystem\Filesystem
     */
    protected $files;

    /**
     * 配置路径
     *
     * @var string
     */
    protected $path;

    /**
     * 默认路径
     *
     * @var string
     */
    protected $defaultPath;

    /**
     * 已加载列表
     *
     * @var string
     */
    protected $required;

    public function __construct(Filesystem $files, $path = null)
    {
        $this->files = $files;
        
        $this->path = $path;
    }

    /**
     * 加载路由配置
     *
     * @param string $name
     * @return mixed
     */
    public function load($name)
    {
        $this->required($this->getPath() . '/' . $name . '.php');
        $this->loadDirectory($name);
    }

    /**
     * 加载目录中的配置
     *
     * @param string $name
     * @return void
     */
    protected function loadDirectory($name)
    {
        $directory = $this->getPath() . '/' . $name;
        if (! $this->files->isDirectory($directory))
        {
            return;
        }
        
        $files = $this->files->files($directory);
        foreach ($files as $file)
        {
            $this->required($file);
        }
    }

    /**
     * 路由配置是否存在
     *
     * @param string $name
     * @return boolean
     */
    public function exists($name)
    {
        $path = $this->getPath() . '/' . $name;
        return $this->files->isDirectory($path) || $this->files->isFile($path . '.php');
    }

    /**
     * 加载配置
     *
     * @param string $file
     * @return void
     */
    public function required($file)
    {
        $md5 = md5($file);
        
        if (isset($this->required[$md5]))
        {
            return $this->required[$md5];
        }
        
        $this->required[$md5] = $this->files->getRequire($file);
    }

    /**
     * 设置默认路由配置路径
     *
     * @param string $path
     * @return void
     */
    public function setDefaultPath($path)
    {
        $this->defaultPath = realpath($path);
    }

    /**
     * 获取路由配置路径
     *
     * @param string $path
     * @return string
     */
    public function getPath()
    {
        return $this->path ? realpath($this->path) : $this->defaultPath;
    }
}