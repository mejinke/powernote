<?php
namespace Powernote\Database\Table;

interface TableInterface
{

    /**
     * 获取主键名
     *
     * @return string
     */
    public function getPrimaryKey();

    /**
     * 获取主键值
     *
     * @return int|string
     */
    public function getPrimaryValue();

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTableName();

    /**
     * 获取数据库名
     *
     * @return string
     */
    public function getDatabaseName();

    /**
     * 设置字段数据
     *
     * @param array $data
     * @return void
     */
    public function setData(Array $data);

    /**
     * 将表中的字段数据以数组的形式返回
     *
     * @return array|null
     */
    public function toArray();

    /**
     * 获取表字段总数
     *
     * @return int
     */
    public function getCount();

    /**
     * 是否存在主键名
     *
     * @return bool
     */
    public function hasPrimaryKey();

    /**
     * 将表数据插入到数据库
     *
     * @return int 插入后的主键ID
     */
    public function insert();

    /**
     * 更新数据库中的表数据
     *
     * @return bool 是否更新成功
     */
    public function update();

    /**
     * 删除数据库中的表数据
     *
     * @return bool 是否删除成功
     */
    public function delete();

    /**
     * 数据成功更新后执行
     *
     * @param string|array $conditions 更新的条件
     * @param array $data 更新的内容
     * @return void
     */
    public function __update($conditions, Array $data);

    /**
     * 数据插入数据库之前执行
     *
     * @param array $data 插入的内容
     * @return void
     */
    public function __insertBefore(Array &$data);

    /**
     * 数据成功插入数据库之后执行
     *
     * @param int $pk_id 插入后的主键Id
     * @param array $data 插入的内容
     * @return void
     */
    public function __insert($pk_id, Array $data);

    /**
     * 删除数据前执行
     *
     * @param string|array $conditions 删除的条件
     * @return void
     */
    public function __removeBefore($conditions);

    /**
     * 数据成功删除后执行
     *
     * @param string|array $conditions 删除的条件
     * @return void
     */
    public function __remove($conditions);
}