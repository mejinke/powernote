<?php
namespace Powernote\Routing;

use Powernote\Controller\Front;
use Powernote\Filesystem\Filesystem;
use Powernote\Support\Facades\Facade;
use Powernote\Routing\Exception\NotFoundException;
use Powernote\Routing\Exception\InvalidUrlException;

/**
 * 自动、通用路由
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Automate implements RouteInterface
{

    /**
     * Application
     *
     * @var \Powernote\Foundation\Application
     */
    protected $app;

    /**
     * 控制器名称
     *
     * @var string
     */
    protected $controller;

    /**
     * Action名称
     *
     * @var string
     *
     */
    protected $action;

    /**
     * 控制器后缀名
     *
     * @var string
     */
    const CONTROLLER_PREFIX = 'Controller';

    /**
     * 文件系统类
     *
     * @var \Powernote\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Request
     *
     * @var \Powernote\Net\Request
     */
    protected $request;
    
    public function __construct()
    {
        $this->app = Facade::getFacadeApplication();
        $this->files = new Filesystem();
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
        $parts = $this->checkRequestUrl($request);
        $len = count($parts);

        switch ($len)
        {
            // 网站根目录 /
            case 0:
                $this->setDefault();
                break;
            // 指定的app或默认app控制器
            case 1:
                // 不允许显式的访问默认app的首页
                if (strtolower($parts[0]) == 'index')
                {
                    throw new InvalidUrlException('Access denied');
                }
                // 是否为控制器
                if ($this->isController($parts[0]))
                {
                    $this->controller = $parts[0];
                }
                break;
            default:
                // 不允许显式的访问默认app的首页
                if (strtolower($parts[0]) == 'index' && strtolower($parts[1]) == 'index')
                {
                    throw new InvalidUrlException('Access denied');
                }
                // 是否为Action
                if ($this->isController($parts[0]))
                {
                    $this->controller = $parts[0];
                    $this->action = $parts[1] ?  : 'index';
                }
                break;
        }
        
        if ($this->controller == '' && $this->action == '')
        {
            throw new NotFoundException('Not Found');
        }
   
        $this->setDefault();
        
        return true;
    }

    /**
     * 给定的控制器是否存在
     *
     * @param string $appName
     * @param string $controllerName
     * @return bool
     */
    private function isController($controllerName)
    {
        return $this->files->isFile($this->app['path.app'] . '/' . lcfirst($this->app->getAppName()) . '/controllers/' . ucfirst($controllerName) . self::CONTROLLER_PREFIX . '.php');
    }

    /**
     * 设置默认的APP、控制器、action
     *
     * @return void
     */
    private function setDefault()
    {
        $this->controller = $this->controller ?  : 'Index';
        $this->action = $this->action ?  : 'index';
    }

    /**
     * 检查请求的URL是否有效
     *
     * @param \Powernote\Net\Request $request
     * @return array
     * @throws InvalidUrlException
     */
    private function checkRequestUrl(\Powernote\Net\Request $request)
    {
        if ($request->url == '/')
        {
            return [];
        }
        
        $parts = explode('/', $request->url);
        foreach ($parts as $k => $part)
        {
            if ($part == '' && isset($parts[$k - 1]) && $parts[$k - 1] == '')
            {
                throw new InvalidUrlException('Requested URL is not valid');
            }
        }
        return array_values(array_filter($parts, function ($part)
        {
            if ($part != '')
            {
                return $part;
            }
        }));
    }

    /**
     * @see \Powernote\Routing\RouteInterface::response()
     */
    public function response()
    {
        return (new Front())->setController($this->controller)
            ->setAction($this->action)
            ->setParameters($this->request->query->all())
            ->callMethod();
    }
}
