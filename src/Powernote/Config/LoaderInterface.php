<?php
namespace Powernote\Config;

/**
 * 配置文件加载类接口
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
interface LoaderInterface
{

    /**
     * 加载指定的配置文件
     *
     * @param string $environment 环境
     * @param string $group 组
     * @param string $namespace
     * @return array
     */
    public function load($environment, $group, $namespace = null);

    /**
     * 指定的配置文件是否存在
     *
     * @param string $group
     * @param string $namespace
     * @return bool
     */
    public function exists($group, $namespace = null);
}