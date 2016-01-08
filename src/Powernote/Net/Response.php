<?php
namespace Powernote\Net;

/**
 * 请求响应基础类
 *
 * @author tian <mejinke@gmail.com>
 *
 */

/**
 * 
 *
 * @author tian <mejinke@gmail.com> Dec 29, 2015 2:27:30 PM
 *
 */
class Response
{

    /**
     * 主体内容
     * 
     * @var mixed
     */
    protected $body = null;

    /**
     * 状态码
     * 
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Content Type
     * 
     * @var string
     */
    protected $contentType = 'text/html';
    
    /**
     * coookie
     * 
     * @var array[\Powernote\Net\Cookies]
     */
    protected $cookies;
    
    /**
     * header
     * @var array[\Powernote\Net\Cookies]
     */
    protected $headers;
    
    /**
     * 初始化
     * 
     * @param string $body
     * @param int $statusCode
     */
    public function __construct($body = null, $statusCode = 200)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }
    
    /**
     * 获取Body
     * 
     * @return string|null
     */
    public function getBody()
    {
        $this->body;
    }
    
    /**
     * 设置Body
     * 
     * @param string $body
     * @return Response
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    
    /**
     * 设置Cookie
     * 
     * @param Cookies $cookie
     * @return Response
     */
    public function setCookie(Cookies $cookie)
    {
        $this->cookies = $cookies;
        return $this;
    }
    
    /**
     * 设置Header
     * 
     * @param Headers $header
     * @return Response
     */
    public function setHeader(Headers $header)
    {
        $this->headers = $header;
        return $this;
    }
    
}