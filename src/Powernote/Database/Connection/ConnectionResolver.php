<?php
namespace Powernote\Database\Connection;

/**
 * 数据库连接解析类
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class ConnectionResolver implements ConnectionResolverInterface
{

    /**
     * 连接列表
     *
     * @var array[\Powernote\Database\Connection]
     */
    protected $connections = [];

    /**
     * 默认连接
     *
     * @var string
     */
    protected $default;

    /**
     * 实例化
     *
     * @param array[\Powernote\Database\Connection] $connections
     */
    public function __construct(array $connections = [])
    {
        foreach ($connections as $name => $connection)
        {
            $this->addConnection($name, $connection);
        }
    }

    /**
     * 获取一个连接
     *
     * @param string $name 连接名称
     * @return \Powernote\Database\Connection
     */
    public function connection($name = null)
    {
        return $this->connections[is_null($name) ? $this->getDefaultConnection() : $name];
    }

    /**
     * 获取默认连接名称
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->default;
    }

    /**
     * 设置默认连接
     *
     * @param string $name 连接名称
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->default = (string) $name;
    }

    /**
     * 添加连接
     *
     * @param string $name 连接名称
     * @param Connection $connection
     * @return void
     */
    public function addConnection($name, Connection $connection)
    {
        $this->connections[$name] = $connection;
    }

    /**
     * 是否存在指定名称的连接
     *
     * @param string $name 连接名称
     * @return bool
     */
    public function hasConnection($name)
    {
        return isset($this->connections[$name]);
    }
}