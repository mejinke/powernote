<?php
namespace Powernote\Container;

use Closure;
use ArrayAccess;

/**
 * 简单的容器实现
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Container implements ArrayAccess
{

    /**
     * 绑定内容列表
     *
     * @var array
     */
    private $bindings;

    /**
     * 共享列表
     *
     * @var array
     */
    private $shares;

    /**
     * 绑定内容到容器
     *
     * @param array|string $name
     * @param mixed $concrete
     * @param boolean $share
     * @return void
     */
    public function bind($name, $concrete = null, $share = true)
    {
        $items = [];
        
        if (is_array($name))
        {
            $items = $name;
        }
        else
        {
            $items[(string) $name] = $concrete;
        }
        
        foreach ($items as $name => $concrete)
        {
            if ($share == true && $this->isShare($name) == false)
            {
                continue;
            }
            
            if (is_null($concrete))
            {
                continue;
            }
            
            $this->bindings[$name] = $this->getClosure($concrete);
            
            if ($share == true)
            {
                $this->shares[$name] = true;
            }
        }
    }

    /**
     * 给定的内容是否为共享
     *
     * @param string $name
     * @return boolean
     */
    public function isShare($name)
    {
        if (isset($this->bindings[$name]) == false) return true;
        
        return isset($this->shares[$name]);
    }

    /**
     * 获取一个Closure
     *
     * @param mixed $concrete
     * @return \Closure
     */
    public function getClosure($concrete)
    {
        if ($concrete instanceof \Closure)
        {
            return function ($container) use($concrete)
            {
                static $object;
                if (null === $object)
                {
                    $object = $concrete($container);
                }
                return $object;
            };
        }
        
        return function ($container) use($concrete)
        {
            return $concrete;
        };
    }

    /**
     * 绑定一个非共享的单例模式内容
     *
     * @param array|string $name
     * @param \Closure|string|null $concrete
     * @return void
     */
    public function singleton($name, $concrete)
    {
        $this->bind($name, $concrete, false);
    }

    /**
     * 绑定一个共享内容
     * 
     * @param array|string $name
     * @param \Closure|string|null $concrete
     * @return void
     */
    public function share($name, $concrete)
    {
        $this->bind($name, $concrete);
    }

    public function offsetExists($offset)
    {
        return isset($this->bindings[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->bindings[$offset]) == false) return null;
        
        return $this->bindings[$offset]($this);
    }

    public function offsetSet($offset, $value)
    {
        if ($this->isShare($offset))
        {
            $this->bindShared($offset, $value);
        }
    }

    public function offsetUnset($offset)
    {
        return;
    }
}