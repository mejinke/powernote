<?php
namespace Powernote\Controller;

/**
 * 控制器接口
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
interface ControllerInterface
{

    /**
     * 执行Action
     * 
     * @param string $method
     * @param array $parameters
     * @return void
     */
    public function call($method, array $parameters);

    /**
     * 是否存在某个Action
     *
     * @param string $method
     * @return bool
     */
    public function has($method);

    /**
     * 获取参数
     *
     * @param string $key
     * @param mixed $default 默认值【默认为null】
     * @return string
     */
    public function getParam($key, $default = null);

    /**
     * 获取所有参数
     *
     * @return array
     */
    public function getParams();

    /**
     * 获取参数，预计值是数字
     *
     * @param string $key
     * @param number $default 默认值【默认为0】
     * @return int
     */
    public function getParamNumber($key, $default = 0);
}