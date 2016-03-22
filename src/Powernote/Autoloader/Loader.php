<?php
namespace Powernote\Autoloader;

use Powernote\Support\Facades\Facade;

/**
 * 自动加载基础类
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
abstract class Loader
{

    /**
     * 命名空间列表
     *
     * @var array
     */
    protected static $namespaces = [];

    /**
     * 目录
     *
     * @var array
     */
    private $directories = [];

    /**
     * 添加类加载目录
     *
     * @param array $directories
     * @param bool $prepend 放在最前
     */
    public function addDirectories(array $directories, $prepend = false)
    {
        $this->directories = array_unique(array_merge($directories, $this->directories));
    }

    /**
     * 获取加载目录
     *
     * @return array
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * 添加名称空间路径
     *
     * @param string $prefix 名称空间前缀
     * @param string $path 路径
     * @param bool $prepend 是否添加到最前端？默认是 false
     */
    public function addNamespace($prefix, $path, $prepend = false)
    {
        // 确保空间名称以"\"开始
        $prefix = trim($prefix, '\\') . '\\';
        
        // 获取一个正确的目录，末尾使用 "/" 分隔
        $path = rtrim($path, '/') . DIRECTORY_SEPARATOR;
        $path = rtrim($path, DIRECTORY_SEPARATOR) . '/';
        
        // 初始化命名空间列表
        if (isset(static::$namespaces[$prefix]) === false)
        {
            static::$namespaces[$prefix] = [];
        }
        
        // 如果需要，将该目录添加到该命名空间的最前面
        if ($prepend)
        {
            array_unshift(static::$namespaces[$prefix], $path);
        }
        else
        {
            array_push(static::$namespaces[$prefix], $path);
        }
    }

    /**
     * 加载类文件
     *
     * @param string $prefix 名称空间前缀
     * @param string $real_class 真实类名称
     * @return bool
     */
    protected function loadNamespaceFile($prefix, $real_class)
    {
        if (isset(static::$namespaces[$prefix]) === false) return false;
        
        foreach (static::$namespaces[$prefix] as $dir)
        {
            $file = $dir . str_replace('\\', DIRECTORY_SEPARATOR, $real_class) . '.php';
            $file = $dir . str_replace('\\', '/', $real_class) . '.php';
            $file = $this->checkAppNamespaceFilePath($prefix, $file);
            if (file_exists($file)) return $file;
        }
        
        return false;
    }

    /**
     * 加载类文件
     *
     * @param string $class
     * @return bool
     */
    protected function loadClass($class)
    {
        $class = $this->normalizeClass($class);
        foreach ($this->directories as $directorie)
        {
            if (file_exists($directorie . '/' . $class))
            {
                require_once $directorie . '/' . $class;
                return true;
            }
        }
        return false;
    }

    /**
     * 获取标准格式的类文件名
     *
     * @param string $class
     * @return string
     */
    protected function normalizeClass($class)
    {
        if ($class[0] == '\\')
        {
            $class = substr($class, 1);
        }
        return str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
    }

    /**
     * 检查当前应用名称空间文件路径
     *
     * @param string $namespacePrefix
     * @param string $file
     * @return string;
     */
    protected function checkAppNamespaceFilePath($namespacePrefix, $file)
    {
        if (class_exists('Powernote\Support\Facades\Facade') && Facade::getFacadeApplication() != null)
        {
            $app = Facade::getFacadeApplication();
            if ('App\\' == $namespacePrefix)
            {
                
                $path = str_replace($app['path.app'], '', $file);
                $fileName = pathinfo($file, PATHINFO_BASENAME);
                
                $exp = explode('/', $path);
                array_pop($exp);
                $path = strtolower(implode('/', $exp));
                
                return $app['path.app'] . $path . '/' . $fileName;
            }
        }
        return $file;
    }
}