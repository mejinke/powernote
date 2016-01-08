<?php
namespace Powernote\Support;

/**
 * 一个通用的集合类，可以通过操作数组的方式访问、遍历
 * ClassName: Powernote\Support$Collection
 * date: Jun 10, 2015 4:04:29 PM
 *
 * @author tian <mejinke@gmail.com>
 */
class Collection implements \ArrayAccess, \Countable, \Iterator
{

    /**
     * 集合数据
     *
     * @var array
     */
    protected $data;

    /**
     * 初始化集合
     *
     * @param array $data 集合数据
     */
    public function __construct(Array $data = [])
    {
        $this->data = $data;
    }

    /**
     * 获取数据
     *
     * @param string $key 键
     * @param mixed $default 如果不存在时返回的默认值
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if ($this->has($key))
        {
            $isInvokable = is_object($this->data[$this->normalizeKey($key)]) && method_exists($this->data[$this->normalizeKey($key)], '__invoke');
            return $isInvokable ? $this->data[$this->normalizeKey($key)]($this) : $this->data[$this->normalizeKey($key)];
        }
        return $default;
    }

    /**
     * 设置一个值
     *
     * @param string $key 键
     * @param mixed $value 值
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$this->normalizeKey($key)] = $value;
    }

    /**
     * 指定的数据是否存在
     *
     * @see ArrayAccess::offsetExists()
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * 获取数据
     *
     * @see ArrayAccess::offsetGet()
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * 设置内容
     *
     * @see ArrayAccess::offsetSet()
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * 删除值
     *
     * @see ArrayAccess::offsetUnset()
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($key);
    }

    /**
     * 获取集合内容总数
     *
     * @see Countable::count()
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * 获取当前数据值
     *
     * @see Iterator::current()
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * 获取当前集合的key值
     *
     * @see Iterator::key()
     * @return mixed
     */
    public function key()
    {
        return $this->keys();
    }

    /**
     * 获取集合中的下一个值
     *
     * @see Iterator::next()
     * @return mixed
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * 重置集合
     *
     * @see Iterator::rewind()
     * @return void
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * 检查当前集合的key是否有效
     *
     * @see Iterator::valid()
     * @return bool
     */
    public function valid()
    {
        $key = key($this->data);
        return ($key !== NULL && $key !== FALSE);
    }

    /**
     * 删除
     *
     * @param string $key 键
     * @return void
     */
    public function remove($key)
    {
        if ($this->has($key))
        {
            unset($this->data[$this->normalizeKey($key)]);
        }
    }

    /**
     * 清空所有的值
     *
     * @return void
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * 获取所有的值
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * 获取所有的key
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * 是否存在某个key
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($this->normalizeKey($key), $this->data);
    }

    /**
     * 标准化键值，主要用于子类实现具体操作
     *
     * @param string $key
     * @return mixed
     */
    public function normalizeKey($key)
    {
        return $key;
    }
}