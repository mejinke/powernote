<?php
namespace Powernote\Events;

use Powernote\Container\Container;

class Dispatcher
{

    /**
     * Container
     *
     * @var \Powernote\Container\Container
     */
    protected $container;

    /**
     * 监听列表
     *
     * @var array
     */
    protected $listens;

    protected $fired = [];

    /**
     * 创建事件调度器实例
     *
     * @param \Powernote\Container\Container $container
     * @return void
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * 监听事件
     *
     * @param array|string $events
     * @param mixed $listener
     * @param number $priority
     * @return void
     */
    public function on($events, $listener, $priority = 0)
    {
        foreach ((array) $events as $event)
        {
            $this->listens[$event][$priority][] = $this->makeListen($listener);
        }
    }

    /**
     * 创建Listen
     *
     * @param mixed $listener
     * @return \Closure
     */
    public function makeListen($listener)
    {
        if (is_string($listener))
        {}
        
        return $listener;
    }

    /**
     * 调用事件的收听者
     * 
     * @param string $event
     * @param mixed $scopes
     * @param bool $halt
     * @return array|null
     */
    public function invoke($event, $scopes = [], $halt = false)
    {
        $responses = [];
        
        if (! is_array($scopes)) $scopes = [$scopes];
        
        $this->fired[] = $event;
        
        foreach ($this->getListeners($event) as $listener)
        {
            $response = call_user_func_array($listener, $scopes);
            
            // 如果响应结果不为空，并且强制停止时则不再调用其余listener
            if (! is_null($response) && $halt == true)
            {
                array_pop($this->fired);
                
                return $response;
            }
            
            if ($response == false) break;
            
            $responses[] = $response;
        }
        
        array_pop($this->fired);
        
        return $halt ? null : $responses;
    }

    
    public function getListeners($event)
    {
        return $this->listens[$event];
    }
}