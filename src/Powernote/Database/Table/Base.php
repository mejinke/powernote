<?php
namespace Powernote\Database\Table;

/**
 * 数据表公共、必要的属性
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Base
{

    /**
     * 主键名称
     *
     * @var string
     */
    protected $pkName;

    /**
     * 主键值
     *
     * @var int|string
     */
    private $pkValue;

    /**
     * 表名称
     *
     * @var string
     */
    protected $tableName;

    /**
     * 数据库名称
     *
     * @var string
     */
    protected $databaseName;

    /**
     * 获取主键名
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->pkName;
    }

    /**
     * 是否存在主键名
     *
     * @return bool
     */
    public function hasPrimaryKey()
    {
        return $this->pkName != null;
    }

    /**
     * 获取主键值
     *
     * @return mixed
     */
    public function getPrimaryValue()
    {
        return $this->pkValue;
    }

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * 获取数据库名
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }
}