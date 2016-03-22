<?php
namespace Powernote\Routing;

/**
 * 路由管理器，所有的路由都将添加到该类中，由该类统一管理、调度
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Router
{

    /**
     * 路由器列表
     *
     * @var RouteInterface array
     */
    protected $routes = ['*' => []];

    /**
     * 匹配到的路由
     *
     * @var RouteInterface
     */
    protected $routed;

    /**
     * 执行前的回调
     * 
     * @var array
     */
    protected $beforeCallbacks = [];
    
    /**
     * 完成解析后的回调
     * 
     * @var array
     */
    protected $afterCallbacks = [];
    
    
    /**
     * 路由器列表是否为空?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->routes) == 1 && count($this->routes['*']) == 0;
    }

    /**
     * 获取路由器列表
     *
     * @return \Powernote\Routing\RouteInterface array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * 任何请求
     *
     * @param string $pattern
     * @param mixed $callback
     * @return void
     */
    public function any($pattern, $callback)
    {
        $this->add([new Route(['*'], $pattern, $callback)]);
    }

    /**
     * Get请求
     *
     * @param string $pattern
     * @param mixed $callback
     * @return void
     */
    public function get($pattern, $callback)
    {
        $this->add([new Route(['GET'], $pattern, $callback)]);
    }

    /**
     * Post请求
     *
     * @param string $pattern
     * @param mixed $callback
     * @return void
     */
    public function post($pattern, $callback)
    {
        $this->add([new Route(['POST'], $pattern, $callback)]);
    }

    /**
     * 添加路由列表
     *
     * @param array[RouteInterface] $routes
     * @param bool $prepend 是否添加到所有路由的最前面【最前的路由优先匹配】，默认是false
     * @return Router
     */
    public function add(Array $routes, $prepend = false)
    {
        foreach ($routes as $route)
        {
            if ($route instanceof RouteInterface)
            {
                $prepend === false ? array_push($this->routes['*'], $route) : array_unshift($this->routes['*'], $route);
            }
        }
    }

    /**
     * 添加一个组路由
     * e.g.
     * 组名：/ucenter，$routes中某个路由重写规则是：/show/@id:\d+ 那么对应的访问URL类似“/ucenter/show/1”、“/ucenter/show/123”
     * 组名为URL的前缀，组名之后的部份匹配路由定义的规则
     *
     * @param string $name 组名
     * @param array[RouteInterface] $routes 路由器数组
     * @param bool $prepend 是否添加到该组路由器列表的最前面【最前的路由优先匹配】，默认是false
     * @return Router
     */
    public function addGroup($name, array $routes, $prepend = false)
    {
        // 格式化组名
        $name = $this->formatGroupName($name);
        
        // 初始化组
        if (! isset($this->routes[$name]))
        {
            $this->routes[$name] = [];
        }
        
        // 添加路由到组
        foreach ($routes as $route)
        {
            if ($route instanceof RouteInterface)
            {
                $prepend === false ? array_push($this->routes[$name], $route) : array_unshift($this->routes[$name], $route);
            }
        }
    }

    /**
     * 格式化组名称
     *
     * @param string $name
     * @return string 格式化后的组名
     */
    protected function formatGroupName($name)
    {
        // 组名必须是以 / 开始
        $name = '/' . $name;
        
        while (strpos($name, '//') !== false)
        {
            $name = str_replace('//', '/', $name);
        }
        
        // 结尾不能是 /
        if (substr($name, - 1) == '/')
        {
            $name = substr($name, 0, mb_strlen($name) - 1);
        }
        
        return $name;
    }

    /**
     * Before
     * 
     * @param \Closure|string $callback
     */
    public function before($callback)
    {
        $this->beforeCallbacks[] = $callback;
    }
    
    /**
     * After
     *
     * @param \Closure|string $callback
     */
    public function after($callback)
    {
        $this->afterCallbacks[] = $after;
    }
    
    /**
     * 根据请求指派路由
     *
     * @param \Powernote\Net\Request $request
     * @param bool $resetRouted 重置之前可能存在的路由结果【重新路由】
     * @return \Powernote\Net\Response|null
     */
    public function dispatch(\Powernote\Net\Request $request, $resetRouted = false)
    {
        // 如果之前已成功匹配到路由则直接返回上次匹配到的路由
        if ($this->routed instanceof RouteInterface && $resetRouted === false)
        {
            return $this->routed->response();
        }
        
        // 如果路由器列表为空则直接返回
        if ($this->isEmpty())
        {
            return null;
        }
        
        // 只保留问号左侧的Path信息
        $request->url = current(explode('?', $request->url));
        
        // 优先处理组路由
        $routes = $this->matchGroup($request);
        
        // 如果没有设置任何路由则返回空
        if (count($routes) == 0)
        {
            return null;
        }
        
        // 遍历所有的路由，如果匹配成功则返回该路由
        foreach ($routes as $route)
        {
            if ($route->match($request))
            {
                $this->routed = $route;
                return $this->routed->response();
            }
        }
       
        return null;
    }

    /**
     * 匹配组，如果匹配到则使用该组内的路由器进行路由
     *
     * @param \Powernote\Net\Request $request
     * @return RouteInterface array
     */
    protected function matchGroup(\Powernote\Net\Request &$request)
    {
        // 没有设置路由组
        if (count($this->routes) == 1)
        {
            return $this->routes['*'];
        }
        
        foreach ($this->routes as $group => $routes)
        {
            // 普通路由则跳过不处理
            if ($group == '*')
            {
                continue;
            }
            // 请求的URL起始位置就是要匹配的组名称
            if (strpos($request->url, $group) === 0)
            {
                $this->normalRequestUrl($request, $group);
                return $routes;
            }
        }
        
        // 当前请求的URL不存在组中
        return $this->routes['*'];
    }

    /**
     * URL中存在该组名时，则将URL中的组名删除
     *
     * @param \Powernote\Net\Request $request
     * @param string $url
     * @return void
     */
    protected function normalRequestUrl(\Powernote\Net\Request &$request, $group = '')
    {
        // 如果没有设置则直接返回URL
        if ($group == '')
        {
            return;
        }
        
        // 组是从URL的起始位置开始检查的，如果存在则删除URL中的组名
        // 路由器匹配时是从组名之后开始匹配
        if (strpos($request->url, $group) === 0)
        {
            $request->url = substr($request->url, strlen($group));
        }
        // URL可能就是组名，这样会导致空URL的错误，所以空URL时强制修改为“/"。
        // 这样相当于是组的首页根
        $request->url = $request->url == '' ? '/' : $request->url;
    }
}
