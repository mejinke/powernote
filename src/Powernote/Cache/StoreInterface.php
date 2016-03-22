<?php
namespace Powernote\Cache;

/**
 * 缓存基础接口
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
interface StoreInterface
{

    /**
     * 获取缓存中的内容
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * 缓存一个项目
     *
     * @param string $key 缓存键名
     * @param mixed $value 缓存内容
     * @param int $minutes 缓存时间【分钟】
     * @return void
     */
    public function put($key, $value, $minutes);

    /**
     * 使缓存中的一个值自动增加
     *
     * @param string $key 缓存键名
     * @param int $value
     * @return int
     */
    public function increment($key, $value = 1);

    /**
     * 使缓存中的一个值自动减少
     *
     * @param string $key 缓存键名
     * @param int $value
     * @return int
     */
    public function decrement($key, $value = 1);

    /**
     * 缓存一个永不过期的项
     *
     * @param string $key 缓存键名
     * @param int $value
     * @return void
     */
    public function forever($key, $value);

    /**
     * 删除一个缓存项
     *
     * @param string $key 缓存键名
     * @return void
     */
    public function remove($key);

    /**
     * 清空所有的缓存项
     *
     * @return void
     */
    public function flush();

    /**
     * 获取缓存键名的前缀
     *
     * @return string
     */
    public function getPrefix();
}