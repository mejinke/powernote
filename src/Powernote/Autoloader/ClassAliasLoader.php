<?php
namespace Powernote\Autoloader;

/**
 * 类别名加载器
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class ClassAliasLoader extends Loader
{

    /**
     * 加载器
     *
     * @var \Powernote\Autoloader\ClassAliasLoader
     */
    private static $loader;

    /**
     * 类别名列表
     *
     * @var array
     */
    private static $aliasMap = [];

    /**
     * 是否已加载
     *
     * @var bool
     */
    private $registerd;

    /**
     * 初始化加载器
     *
     * @return \Powernote\Autoloader\ClassAliasLoader
     */
    public static function init()
    {
        if (static::$loader == null)
        {
            static::$loader = new self();
        }
        return static::$loader;
    }

    /**
     * 注册自动加载
     *
     * @return \Powernote\Autoloader\ClassAliasLoader
     */
    public function register()
    {
        if ($this->registerd !== true)
        {
            spl_autoload_register([static::$loader, 'loadClass'], true, true);
            $this->registerd = true;
        }
        return $this;
    }

    /**
     * 添加别名
     *
     * @param string $alias 别名
     * @param string $path 类地址
     * @return \Powernote\Autoloader\ClassAliasLoader
     */
    public function add($alias, $path)
    {
        return $this->adds([$alias => $path]);
    }

    /**
     * 添加别名列表
     *
     * @param array $alias
     * @return \Powernote\Autoloader\ClassAliasLoader
     */
    public function adds(array $alias)
    {
        static::$aliasMap += $alias;
        return $this;
    }

    /**
     * 获取别名对应的类名
     *
     * @param string $alias 别名
     * @return string
     */
    public function get($alias)
    {
        if ($this->has($alias) !== true) return false;
        
        return static::$aliasMap[$alias];
    }

    /**
     * 删除一个别名
     *
     * @param string $alias 别名名称
     * @return \Powernote\Autoloader\ClassAliasLoader
     */
    public function remove($alias)
    {
        if ($this->has($alias)) unset(static::$aliasMap[$alias]);
        
        return $this;
    }

    /**
     * 别名是否存在
     *
     * @param string $alias 别名名称
     * @return bool
     */
    public function has($alias)
    {
        return isset(static::$aliasMap[$alias]);
    }

    /**
     * 自动加载
     *
     * @param string $alias 别名
     * @return bool
     */
    public function loadClass($alias)
    {
        if ($this->has($alias) !== true) return false;
        
        return class_alias($this->get($alias), $alias);
    }
}
