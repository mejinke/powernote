<?php
namespace Powernote\Autoloader;

require __DIR__ . '/Loader.php';

/**
 * 类自动加载
 *
 * @author tian <mejinke@gmail.com>
 *
 */
class ClassLoader extends Loader
{

    /**
     * 加载器
     *
     * @var \Powernote\Autoloader\ClassLoader
     */
    private static $loader;

    /**
     * 是否已加载
     *
     * @var bool
     */
    private $registerd;

    /**
     * 初始化加载器
     *
     * @return \Powernote\Autoloader\ClassLoader
     */
    public static function init()
    {
        if (static::$loader == null)
        {
            static::$loader = new self();
            // 添加框架名称空间加载路径
            static::$loader->addNamespace('Powernote', realpath(__DIR__ . '/../'), true);
        }
        return static::$loader;
    }

    /**
     * 注册自动加载
     *
     * @return \Powernote\Autoloader\ClassLoader
     */
    public function register()
    {
        if ($this->registerd !== true)
        {
            spl_autoload_register([static::$loader, 'load'], true, true);
            $this->registerd = true;
        }
        return $this;
    }

    /**
     * 加载
     *
     * @param string $class
     * @return void
     */
    public function load($class)
    {
        $prefix = $class;
        $pos = strrpos($prefix, '\\');
        
        if ($pos === false) return $this->loadClass($class);
        
        while (false !== $pos = strrpos($prefix, '\\'))
        {
            
            $prefix = substr($class, 0, $pos + 1);
            
            // 获取当前真实的类名
            $real_class = substr($class, $pos + 1);
            $file = $this->loadNamespaceFile($prefix, $real_class);
            
            if ($file != false)
            {
                require $file;
            }
            $prefix = rtrim($prefix, '\\');
        }
    }
}