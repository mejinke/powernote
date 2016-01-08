<?php
namespace Powernote\Controller;

use Powernote\Support\Facades\App;
use Powernote\Container\Container;
use Powernote\Autoloader\ClassLoader;
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
     * Container
     *
     * @var \Powernote\Container\Container
     */
    protected $container;

    /**
     * 模块名
     *
     * @var string
     */
    protected $module;

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

    public function __construct(Container $c = null)
    {
        $this->container = $c ?  : App::getFacadeApplication();
    }

    /**
     * 设置模块名
     *
     * @param string $name
     * @return \Powernote\Controller\Front
     */
    public function setModule($name)
    {
        $this->module = $name;
        return $this;
    }

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
     * @return mixed
     * @throws InvalidControllerException
     */
    public function callMethod()
    {
        if ($path = $this->getControllerPath())
        {
            ClassLoader::init()->addDirectories([$path]);
        }
        
        list ($name, $method) = $this->normalizeName();

        $class = new $name();
        
        if (! $class instanceof ControllerInterface)
        {
            throw new InvalidControllerException();
        }
        
        $class->call($method, $this->parameters);
    }

    /**
     * 获取当前要执行的控制器路径
     *
     * @return string
     */
    private function getControllerPath()
    {
        if (in_array($this->module, ['', 'default']))
        {
            return $this->container['path.app'] . '/controllers';
        }
        return $this->container['path.app'] . '/modules/' . $this->module . '/controllers';
    }

    /**
     * 标准化名称
     *
     * @return array
     */
    private function normalizeName()
    {
        return [$this->controller . 'Controller', $this->action . 'Action'];
    }
}