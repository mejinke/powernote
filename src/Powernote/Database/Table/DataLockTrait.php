<?php
namespace Powernote\Database\Table;

/**
 * 简单的锁特性
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
trait DataLockTrait
{

    /**
     * 锁状态
     *
     * @var bool
     */
    private $lock = false;

    /**
     * 上锁时间戳
     *
     * @var int
     */
    private $time = 0;

    /**
     * 上锁
     *
     * @return void
     */
    public function lock()
    {
        $this->time = time();
        $this->lock = true;
    }

    /**
     * 解锁
     *
     * @return void
     */
    public function unLock()
    {
        $this->lock = false;
    }

    /**
     * 获取上锁时间戳，解锁后该值不会清零
     *
     * @return int 时间戳；如果之前从来没有上锁过则返回 0
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * 如果已上锁将会拋出LockException异常
     *
     * @throws LockException
     */
    public function touchLock()
    {
        if ($this->lock === true)
        {
            throw new LockException('Table lock.');
        }
    }
}