<?php
namespace Powernote\Controller;

use Powernote\Support\Facades\App;
use Powernote\Controller\Exception\InvalidControllerException;

/**
 * 前端控制器调度类
 *
 * @author tian <mejinke@gmail.com> Dec 29, 2015 4:35:10 PM
 *        
 */
class Front
{
    /**
     * 控制器名
     *
     * @var string
     */
    protected $controller;

    /**
     * 动作名
     *
     * @var string
     */
    protected $action;

    /**
     * 参数
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * 设置控制器名
     *
     * @param string $name
     * @return \Powernote\Controller\Front
     */
    public function setController($name)
    {
        $this->controller = ucfirst($name);
        return $this;
    }

    /**
     * 设置动作名
     *
     * @param string $name
     * @return \Powernote\Controller\Front
     */
    public function setAction($name)
    {
        $this->action = lcfirst($name);
        return $this;
    }

    /**
     * 设置参数
     *
     * @param array $parameters
     * @return \Powernote\Controller\Front
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * 生成Closure
     *
     * @param string $module
     * @param string $name
     * @param string $method
     * @param array $arguments
     * @return \Powernote\Net\Response
     * @throws InvalidControllerException
     */
    public function callMethod()
    {
        list ($name, $method) = $this->normalizeName();
      
        $class = new $name();
        
        if (! $class instanceof ControllerInterface)
        {
            throw new InvalidControllerException();
        }
       
        return $class->call($method, $this->parameters);
    }


    /**
     * 标准化名称
     *
     * @return array
     */
    private function normalizeName()
    {
        $appName = App::getAppName();
        $class = "\\App\\{$appName}\Controllers\\{$this->controller}Controller";
        return [$class, $this->action . 'Action'];
    }
}