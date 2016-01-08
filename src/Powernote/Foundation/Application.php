<?php
namespace Powernote\Foundation;

use Powernote\Net\Request;
use Powernote\Net\Response;
use Powernote\Routing\Router;
use Powernote\Config\FileLoader;
use Powernote\Events\Dispatcher;
use Powernote\Container\Container;
use Powernote\Filesystem\Filesystem;
use Powernote\Routing\FileLoader as RouteFileLoader;
use Powernote\Foundation\Exception\NotFoundException;

/**
 * 框架应用程序
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Application extends Container
{

    /**
     * 应用执行完成时的回调
     *
     * @var array
     */
    protected $finishCallbacks = [];

    /**
     * 创建一个应用
     *
     * @return void
     */
    public function __construct()
    {
        $this->registerBaseServices();
    }

    /**
     * Before
     *
     * @param \Closure|string $callback
     */
    public function before($callback)
    {
        return $this['router']->before($callback);
    }

    /**
     * After
     *
     * @param \Closure|string $callback
     */
    public function after($callback)
    {
        return $this['router']->after($callback);
    }

    /**
     * Finish
     *
     * @param \Closure|string $callback
     */
    public function finish($callback)
    {
        $this->finishCallbacks[] = $callback;
    }

    /**
     * 设置必要的路径信息
     *
     * @param array $paths
     * @return void
     */
    public function installPaths(array $paths)
    {
        $this->singleton('path', realpath($paths['base']));
        unset($paths['base']);
        foreach ($paths as $key => $path)
        {
            $this->singleton('path.' . $key, realpath($path));
        }
    }

    /**
     * 检测当前运行环境
     *
     * @param array $parameter
     * @return string
     */
    public function detectEnvironment(array $parameter)
    {
        $this->singleton('environment', function ($container) use($parameter)
        {
            return new EnvironmentDetect($parameter, ! empty($_SERVER['argv']) ? $_SERVER['argv'] : null);
        });
        
        $this->singleton('env', $this['environment']->detect());
        return $this['env'];
    }

    /**
     * 获取配置文件加载器
     *
     * @return \Powernote\Config\FileLoader
     */
    public function getConfigLoader()
    {
        return new FileLoader(new Filesystem(), $this['path.app'] . '/config');
    }

    /**
     * 当前是否为本地环境
     *
     * @return boolean
     */
    public function isLocal()
    {
        return $this['env'] == 'local';
    }

    /**
     * 安装路由
     *
     * @param string $path 路由配置路径
     * @return void
     */
    public function installRoute($path = null)
    {
        $loader = new RouteFileLoader(new Filesystem(), $path ?  : $this['path.app'] . '/route');
        $loader->load(Request::getDomainPrefix() ?  : 'www');
    }

    /**
     * 注册基础服务
     *
     * @return void
     */
    protected function registerBaseServices()
    {
        $this->singleton('events', function ($container)
        {
            return new Dispatcher($container);
        });
        
        $this->singleton('router', function ($container)
        {
            return new Router();
        });
    }

    /**
     * 运行框架
     *
     * @param Powernote\Net\Request $request
     * @return mixed
     */
    public function run(Powernote\Net\Request $request = null)
    {
        if ($request === null)
        {
            $request = new Request();
        }
        $this->singleton('request', $request);
        
        $response = $this['router']->dispatch($request);
        if ($response instanceof Response)
        {
            echo $response;
        }
        else
        {
            throw new NotFoundException('Not Found');
        }
    }

    public function response(\Powernote\Net\Response $response)
    {}
}