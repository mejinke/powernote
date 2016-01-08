<?php
namespace Powernote\Routing;

/**
 * 路由器配置加载接口
 *
 * @author tian <mejinke@gmail.com>
 *
 */
interface LoaderInterface
{

    /**
     * 加载路由配置
     * 
     * @param string $name
     * @return mixed
     */
    public function load($name);

    /**
     * 路由配置是否存在
     * 
     * @param string $name
     * @return boolean
     */
    public function exists($name);
}