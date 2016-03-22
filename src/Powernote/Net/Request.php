<?php
namespace Powernote\Net;

use Powernote\Support\String;
use Powernote\Support\Collection;

/**
 * 请求对象，封装了基础的HTTP请求信息
 *
 * @author tian <mejinke@gmail.com>
 */
class Request
{

    /**
     * 当前请求的URL
     *
     * @var string
     */
    public $url;

    /**
     * 当前求最终的URL
     *
     * @var string
     */
    private $realUrl;

    /**
     * 当前URL Path
     *
     * @var string
     */
    public $path;

    /**
     * 请求的方式（GET、POST、PUT、DELETE）
     *
     * @var string
     */
    protected $method;

    /**
     * 引用的URL
     *
     * @var string
     */
    public $referrer;

    /**
     * 请求的客户端IP
     *
     * @var string
     */
    public $ip;

    /**
     * 浏览器信息
     *
     * @var string
     */
    public $user_agent;

    /**
     * 类型
     *
     * @var string
     */
    public $type;

    /**
     * 内容长度
     *
     * @var int
     */
    public $length;

    /**
     * 当前请求的Query
     *
     * @var \Powernote\Support\Collection
     */
    public $query;

    /**
     * POST请求的数据
     *
     * @var \Powernote\Support\Collection
     */
    public $data;

    /**
     * 上传的文件数据
     *
     * @var \Powernote\Support\Collection
     */
    public $files;

    /**
     * COOIKE数据
     *
     * @var \Powernote\Support\Collection
     */
    public $cookies;

    /**
     * 是否为HTTPS?
     *
     * @var bool
     */
    public $secure;

    /**
     * Accept
     *
     * @var string
     */
    public $accept;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * 初始化
     *
     * @return void
     */
    private function init()
    {
        $propertys = [
            'url' => static::getVar('REQUEST_URI', '/'),
            'method' => static::getMethod(),
            'referrer' => static::getVar('HTTP_REFERER'),
            'ip' => static::getClientIp(),
            'ajax' => static::getVar('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest',
            'user_agent' => static::getVar('HTTP_USER_AGENT'),
            'type' => static::getVar('CONTENT_TYPE'),
            'length' => static::getVar('CONTENT_LENGTH', 0),
            'query' => new Collection($this->parseQuery(static::getVar('REQUEST_URI', '/'))),
            'data' => new Collection($_POST),
            'cookies' => new Collection($_COOKIE),
            'files' => new Collection($_FILES),
            'secure' => static::getVar('HTTPS', 'off') != 'off',
            'accept' => static::getVar('HTTP_ACCEPT')
        ];
        
        // 设置属性
        foreach ($propertys as $name => $value)
        {
            $this->$name = $value;
        }
        
        // 设置URL
        $this->realUrl = $this->url;
    }

    /**
     * 获取URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->realUrl;
    }

    /**
     * 获取请求方法
     *
     * @return string 例如 GET、POST、DELETE
     */
    public static function getMethod()
    {
        $method = self::getVar('REQUEST_METHOD', 'GET');
        
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']))
        {
            $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        }
        
        return strtoupper($method);
    }

    /**
     * 是否为一个GET请求
     *
     * @return bool
     */
    public static function isGet()
    {
        return static::getMethod() == 'GET';
    }

    /**
     * 是否为一个POST请求
     *
     * @return bool
     */
    public static function isPost()
    {
        return static::getMethod() == 'POST';
    }

    /**
     * 是否为一个PUT请求
     *
     * @return bool
     */
    public static function isPut()
    {
        return static::getMethod() == 'PUT';
    }

    /**
     * 是否为一个DELETE请求
     *
     * @return bool
     */
    public static function isDelete()
    {
        return static::getMethod() == 'DELETE';
    }

    /**
     * 是否为一个HEAD请求
     *
     * @return bool
     */
    public static function isHead()
    {
        return static::getMethod() == 'HEAD';
    }

    /**
     * 是否为一个OPTIONS请求
     *
     * @return bool
     */
    public static function isOptions()
    {
        return static::getMethod() == 'OPTIONS';
    }

    /**
     * 是否为一个PATCH请求
     *
     * @return bool
     */
    public static function isPatch()
    {
        return static::getMethod() == 'PATCH';
    }

    /**
     * 是否为一个AJAX请求
     *
     * @return bool
     */
    public static function isAjax()
    {
        return static::getVar('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';
    }

    /**
     * 获取客户端最终的IP
     *
     * @access public
     * @param bool $checkProxy 是否检测代理
     * @return string
     */
    public static function getClientIp($checkProxy = true)
    {
        if ($checkProxy && static::getVar('HTTP_CLIENT_IP') != null)
        {
            $ip = static::getVar('HTTP_CLIENT_IP');
        }
        if ($checkProxy && static::getVar('HTTP_X_FORWARDED_FOR') != null)
        {
            $ip = static::getVar('HTTP_X_FORWARDED_FOR');
        }
        else
        {
            $ip = static::getVar('REMOTE_ADDR');
        }
        
        return $ip;
    }

    /**
     * 获取$_SERVER全局变量中的值，如果将要获取的变量值不存在时则返回$default的值
     *
     * @param string $name 变量名
     * @param string $default 默认值
     * @return mixed
     */
    public static function getVar($name, $default = NULL)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }

    /**
     * 解析URL中的参数信息
     *
     * @param string $url URL地址
     * @return array
     */
    public static function parseQuery($url)
    {
        $params = [];
        $args = parse_url($url);
        if (isset($args['query']))
        {
            parse_str($args['query'], $params);
        }
        
        // 过滤参数
        foreach ($params as $k => $value)
        {
            $params[$k] = String::text($value);
        }
        
        return $params;
    }

    /**
     * 获取当前请求的域名前缀
     *
     * @return string
     */
    public static function getDomainPrefix()
    {
        $host = static::getVar('HTTP_HOST');
        
        //如果HOST是IP地址不是域名，则直接返回www
        if (is_numeric(explode('.', $host)[0])) 
        {
            return 'www';
        }
        $prefix = str_replace(static::getVar('SERVER_NAME'), '', static::getVar('HTTP_HOST'));
        return substr($prefix, 0, String::length($prefix) - 1);
    }
}