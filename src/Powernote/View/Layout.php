<?php
namespace Powernote\View;

use Powernote\Support\Facades\App;
use Powernote\View\Exception\TemplateNotFoundException;

/**
 * 视图布局类，所有布局对象必须继承此类，并实现init方法
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
abstract class Layout
{

    /**
     * 布局文件路径
     *
     * @var string
     */
    protected $templatePath;

    /**
     * 布局缓存是否为私有，默认为false
     * true：当Action启用布局时，生成与当前Action唯一的布局缓存。不与其它Action调此同一个布局冲突。
     * false：当Action启用布局时，如果其它Action之前已调用此布局，并且已存在缓存，则直接使用。
     *
     * @var bool
     */
    protected $privateCache = false;

    /**
     * 布局缓存时间
     *
     * @var int
     */
    protected $cacheTime = 0;

    /**
     * 布局页面可调用的数据
     *
     * @var array
     */
    private $data;

    /**
     * 视图内容
     *
     * @var string
     */
    public $viewContent;

    /**
     * 设置布局缓存时间。单位：分钟
     *
     * @param int $minutes
     * @return \Powernote\View\Layout
     */
    public function setCacheTime($minutes)
    {
        if (is_numeric($minutes))
        {
            $this->cacheTime = $minutes;
        }
        return $this;
    }

    /**
     * 获取布局缓存时间
     *
     * @return int
     */
    public function getCacheTime()
    {
        return $this->cacheTime;
    }

    /**
     * 设置为私有布局
     *
     * @return void
     */
    public function setPrivate()
    {
        $this->privateCache = true;
    }

    /**
     * 设置为公用布局
     *
     * @return void
     */
    public function setPublic()
    {
        $this->privateCache = false;
    }

    /**
     * 是否为私有布局
     *
     * @return bool
     */
    public function isPrivate()
    {
        return $this->privateCache;
    }

    /**
     * 获取布局模板文件
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * 添加数据
     *
     * @param string $key
     * @param mixed $value
     * @return \Powernote\View\Layout
     */
    public function assign($key, $value)
    {
        $this->data[(string) $key] = $value;
        return $this;
    }

    /**
     * 渲染布局模板文件
     *
     * @return string
     */
    public function render()
    {}

    /**
     * 查找模板文件
     *
     * @return string
     */
    protected function findTemplate()
    {
        $file = App::getFacadeApplication()['path.app'] . '/layouts/views/' . $this->getViewStyle() . '/' . $this->templatePath;
        if (! is_file($file))
        {
            throw new TemplateNotFoundException('布局模板文件未找到 “' . $file . '”');
        }
        return $file;
    }

    /**
     * 获取模板视图样式名
     *
     * @return string
     */
    protected function getViewStyle()
    {
        return App::getFacadeApplication()['config']['app']['view.style'] ?  : 'default';
    }
}