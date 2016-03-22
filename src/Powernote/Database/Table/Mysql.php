<?php
namespace Powernote\Database\Table;

use Powernote\Support\LockTrait;
/**
 * Mysql数据库Table基础类
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Mysql extends Base implements TableInterface
{
    use LockTrait;

    /**
     * 表数据
     *
     * @var array
     */
    private $data;

    public function setData(array $data)
    {
        $this->touchLock();
        $this->data = $data;
    }

    /**
     * 将表中的字段数据以数组的形式返回
     *
     * @return array|null
     */
    public function toArray()
    {
        return is_array($this->data) ? $this->data : null;
    }

    /**
     * 获取表字段总数
     *
     * @return int
     */
    public function getCount()
    {
        return is_array($this->data) ? count($this->data) : 0;
    }

    /**
     * 将表数据插入到数据库
     *
     * @return int 插入后的主键ID
     */
    public function insert()
    {
        $this->touchLock();
    }

    /**
     * 更新数据库中的表数据
     *
     * @return bool 是否更新成功
     */
    public function update()
    {
        $this->touchLock();
    }

    /**
     * 删除数据库中的表数据
     *
     * @return bool 是否删除成功
     */
    public function delete()
    {
        $this->touchLock();
    }

    /**
     * 数据更新成功后执行
     *
     * @param string|array $conditions 更新的条件
     * @param array $data 更新的内容
     * @return void
     */
    public function __update($conditions, Array $data)
    {}

    /**
     * 数据插入数据库之前执行
     *
     * @param array $data 插入的内容
     * @return void
     */
    public function __insertBefore(Array &$data)
    {}

    /**
     * 数据成功插入数据库之后执行
     *
     * @param int $pk_id 插入后的主键Id
     * @param array $data 插入的内容
     * @return void
     */
    public function __insert($pk_id, Array $data)
    {}

    /**
     * 删除数据前执行
     *
     * @param string $conditions 删除的条件
     * @return void
     */
    public function __removeBefore($conditions)
    {}

    /**
     * 删除数据成功后执行
     *
     * @param string $conditions 删除的条件
     * @return void
     */
    public function __remove($conditions)
    {}
}