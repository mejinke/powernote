<?php
namespace Powernote\Support\Facades;

/**
 * 装饰类
 *
 * @author tian <mejinke@gmail.com> Jan 7, 2016 5:04:14 PM
 *
 */
abstract class Facade
{

    protected static $app;

    protected static $resolvedInstance;

    /**
     * 设置Application
     *
     * @param \Powernote\Foundation\Application $app
     * @return void
     */
    public static function setFacadeApplication(\Powernote\Foundation\Application $app)
    {
        static::$app = $app;
    }

    /**
     * 获取Application
     *
     * @return \Powernote\Foundation\Application
     */
    public static function getFacadeApplication()
    {
        return static::$app;
    }

    /**
     * 清空实例列表
     *
     * @return void
     */
    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    /**
     * 获取操作的实例名
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException("Facade does not implement getFacadeAccessor method.");
    }

    /**
     * 获取当前实例
     *
     * @return mixed
     */
    protected static function getCurrentFacadeInstance()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * 决定使用的实例
     *
     * @param string $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) return $name;
        if (isset(static::$resolvedInstance[$name]))
        {
            return static::$resolvedInstance[$name];
        }
        
        return static::$resolvedInstance[$name] = static::$app[$name];
    }

    /**
     * 调用实例的方法
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public static function __callstatic($method, $args)
    {
        $instance = static::getCurrentFacadeInstance();
        
        switch (count($args))
        {
            case 0:
                return $instance->$method();
            
            case 1:
                return $instance->$method($args[0]);
            
            case 2:
                return $instance->$method($args[0], $args[1]);
            
            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);
            
            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            
            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}