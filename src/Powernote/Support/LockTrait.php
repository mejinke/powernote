<?php
namespace Powernote\Support;


/**
 * 简单的锁特性
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
trait LockTrait
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
     * 上锁【如果之前已上过锁将抛出异常】
     *
     *@throws \Powernote\Support\Exception\LockException
     * @return void
     */
    public function lock()
    {
        $this->touchLock();
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
    public function lockTime()
    {
        return $this->time;
    }

    /**
     * 如果已上锁将会拋出Lock异常
     *
     * @throws \Powernote\Support\Exception\LockException
     */
    public function touchLock()
    {
        if ($this->lock === true)
        {
            throw new \Powernote\Support\Exception\LockException('The current has not been unlocked');
        }
    }
}