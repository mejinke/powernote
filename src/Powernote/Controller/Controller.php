<?php
namespace Powernote\Controller;

use Powernote\Foundation\Exception\NotFoundException;
use Powernote\Controller\Exception\ActionValidateFailureException;

/**
 * 基础控制器
 *
 * @author tian <mejinke@gmail.com> Dec 29, 2015 5:07:21 PM
 *        
 */
abstract class Controller implements ControllerInterface
{

    /**
     * 当前调用的Action
     *
     * @var string
     */
    private $method;

    /**
     * 请求的参数列表
     *
     * @var array
     */
    private $parameters;

    protected $view;

    /**
     *
     * {@inheritDoc}
     *
     * @see \Powernote\Controller\ControllerInterface::call()
     */
    public function call($method, array $parameters)
    {
        $this->method = $method;
        $this->parameters = $parameters;
        
        // Action是否存在
        if ($this->has($method) == false)
        {
            throw new NotFoundException();
        }
        
        // 执行前置验证方法
        if ($this->callValidateMethod($method) !== true)
        {
            throw new ActionValidateFailureException();
        }
        
        call_user_func_array([$this, $method], $this->getBindArguments($method));
    }

    /**
     * 调用前置验证方法
     *
     * @param string $method Action全名
     * @return bool
     */
    private function callValidateMethod($method)
    {
        $method = $this->getValidateMethodName($method);
        
        // 是否存在该方法
        if ($this->has($method) == false)
        {
            return true;
        }
        
        return call_user_func_array([$this, $method], $this->getBindArguments($method)) === true;
    }

    /**
     * 获取前置验证方法名
     *
     * @param string $method Action全名
     * @return string
     */
    private function getValidateMethodName($method)
    {
        return 'validate' . ucfirst(substr($method, 0, strlen($method) - 6));
    }

    /**
     * 获取指定方法可以被正常绑定的参数列表
     *
     * @return array
     */
    protected function getBindArguments($method)
    {
        $parameters = (new \ReflectionMethod($this, $method))->getParameters();
        $arguments = [];
        if (is_array($parameters))
        {
            foreach ($parameters as $p)
            {
                $arguments[$p->name] = isset($this->parameters[$p->name]) ? $this->parameters[$p->name] : null;
            }
        }
        
        return $arguments;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Powernote\Controller\ControllerInterface::has()
     */
    public function has($method)
    {
        return array_search($method, get_class_methods($this)) !== false;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Powernote\Controller\ControllerInterface::getParam()
     */
    public function getParam($key, $default = null)
    {
        return $this->parameters[$key] ?  : $default;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Powernote\Controller\ControllerInterface::getParams()
     */
    public function getParams()
    {
        return $this->parameters;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Powernote\Controller\ControllerInterface::getParamNumber()
     */
    public function getParamNumber($key, $default = 0)
    {
        $val = $this->parameters[$key] ?  : $default;
        
        if (is_numeric($val))
        {
            $tmp = explode('.', $val);
            if (strlen($tmp[0]) < 14)
            {
                $val = floatval($val);
            }
        }
        
        return floatval($val);
    }
}