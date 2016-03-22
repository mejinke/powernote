<?php
namespace Powernote\Routing;

use Powernote\Net\Response;
use Powernote\Controller\Front;
use \Powernote\Routing\Exception\NotFoundException;
use \Powernote\Routing\Exception\InvalidRouteCallbackException;

/**
 * 通用的HTTP URL路由器
 *
 * @author tian <mejinke@gmail.com>
 */
class Route implements RouteInterface
{

    /**
     * Request
     *
     * @var \Powernote\Net\Request
     */
    protected $request;

    /**
     * HTTP请求方法
     *
     * @var array
     */
    protected $methods = [];

    /**
     * 匹配规划
     *
     * @var string
     */
    protected $pattern;

    /**
     * 回调方法
     *
     * @var mixed
     */
    protected $callback;

    /**
     * 参数列表
     *
     * @var array
     */
    protected $params = [];

    /**
     *
     * @var array
     */
    protected $matches = [];
    
    /**
     * 初始化
     *
     * @param array $methods HTTP方法列表
     * @param mixed $pattern 匹配规则
     * @param mixed $callback 回调内容，可以是Closure、字符串("app/controller/action")
     */
    public function __construct(array $methods, $pattern, $callback)
    {
        $methods == [] && $methods = ['*'];
        
        $this->methods = array_filter($methods, function ($method)
        {
            return strtoupper($method);
        });
        
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    /**
     * 匹配
     *
     * @param \Powernote\Net\Request $request
     * @return bool 是否匹配成功？
     */
    public function match(\Powernote\Net\Request $request)
    {
        $this->request = $request;
        
        if (! in_array($request->getMethod(), $this->methods) && ! in_array('*', $this->methods)) return false;
        
        if ($this->pattern == $request->url) return true;
        
        if ($this->pattern == '/') return false;
        
        $first_char = substr($this->pattern, 0, 1);
        
        $last_char = substr($this->pattern, - 1);
        
        $regex = str_replace([')', '/*'], [')?', '(/?|/.*?)'], $this->pattern);
        $regex = preg_replace_callback('#@([\w]+)(:([^/\(\)]*))?#', [$this, 'matchesCallback'], $regex);
        
        $regex .= $last_char == '/' ? '?' : '/?';
        
        if (preg_match('#^' . $regex . '$#', $request->url, $matches))
        {
            foreach ($this->matches as $k => $v)
            {
                $this->params[$k] = (array_key_exists($k, $matches)) ? urldecode($matches[$k]) : null;
            }
            $this->regex = $regex;
            return true;
        }
    }

    /**
     * 匹配参数的回调方法
     *
     * @param array $matches
     * @return string
     */
    protected function matchesCallback($matches)
    {
        $this->matches[$matches[1]] = null;
        if (isset($matches[3]))
        {
            return '(?P<' . $matches[1] . '>' . $matches[3] . ')';
        }
        return '(?P<' . $matches[1] . '>[^/\?]+)';
    }

    /**
     * 执行路由结果
     *
     * @throws InvalidRouteCallbackException
     */
    public function response()
    {
        // 合并Url Query参数
        $this->params += $this->request->query->all();
        
        $res = ['Closure', 'String', 'Controller'];
        
        foreach ($res as $cb)
        {
            $reponse = $this->{'response' . $cb}();
            if (is_string($reponse))
            {
                return new Response($reponse);
            }
            elseif ($reponse instanceof Response)
            {
                return $reponse;
            }
        }
        
        throw new NotFoundException('No route to the result' . $callback);
    }

    /**
     * 通过Closure响应
     *
     * @return false|string
     */
    protected function responseClosure()
    {
        if (is_callable($this->callback))
        {
            $args = [];
            // 反射 Closure参数列表
            $parameters = (new \ReflectionFunction($this->callback))->getParameters();
            if (is_array($parameters))
            {
                foreach ($parameters as $p)
                {
                    $args[$p->name] = isset($this->params[$p->name]) ? $this->params[$p->name] : null;
                }
            }
            return call_user_func_array($this->callback, $args);
        }
        
        return false;
    }

    /**
     * 通过Controller响应
     *
     * @return Front|false
     * @throws InvalidRouteCallbackException
     */
    protected function responseController()
    {
        $callback = $this->callback;
        if (is_string($callback) && strpos($callback, '/') !== false)
        {
            
            $chunks = explode('/', $callback);
            
            switch (count($chunks))
            {
                case 1:
                    array_unshift($chunks, 'index');
                    break;
                case 2:
                    break;
                default:
                    throw new InvalidRouteCallbackException('无效的Callback：' . $callback);
            }
            return (new Front())->setController($chunks[0])
                ->setAction($chunks[1])
                ->setParameters($this->params)
                ->callMethod();
        }
        
        return false;
    }

    /**
     * 响应文本内容
     *
     * @return false|string
     */
    protected function responseString()
    {
        $callback = $this->callback;
        if (is_string($callback))
        {
            $callback = function () use($callback)
            {
                return $callback;
            };
        }
        
        return false;
    }
}