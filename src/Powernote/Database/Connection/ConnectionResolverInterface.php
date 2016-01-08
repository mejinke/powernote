<?php
namespace Powernote\Database\Connection;

/**
 * 数据库连接解析接口
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
interface ConnectionResolverInterface
{

    /**
     * 获取一个连接
     *
     * @param string $name 连接名称
     * @return \Powernote\Database\Connection\Connection
     */
    public function connection($name = null);

    /**
     * 获取默认连接名称
     *
     * @return string
     */
    public function getDefaultConnection();

    /**
     * 设置默认连接
     *
     * @param string $name 连接名称
     * @return void
     */
    public function setDefaultConnection($name);
}