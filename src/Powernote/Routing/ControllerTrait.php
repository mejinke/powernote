<?php
namespace Powernote\Routing;

use Powernote\Support\Facades\App;
use Powernote\Autoloader\ClassLoader;
use Powernote\Routing\Exception\InvalidRouteCallbackException;

/**
 * 主要用于将一个控制器包装成Closure
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
trait ControllerTrait
{

    /**
     * 生成Closure
     *
     * @param string $module
     * @param string $name
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws \Powernote\Routing\Exception\InvalidRouteCallbackException
     */
    public function callMethod($module, $name, $method, $arguments)
    {
        if ($path = $this->getControllerPath($module))
        {
            ClassLoader::init()->addDirectories([$path]);
        }
        
        try
        {
            
            list ($name, $method) = $this->normalizeName($name, $method);
            $parameters = (new \ReflectionMethod($name . '::' . $method))->getParameters();
            $tmp = [];
            if (is_array($parameters))
            {
                foreach ($parameters as $p)
                {
                    $tmp[$p->name] = isset($arguments[$p->name]) ? $arguments[$p->name] : null;
                }
            }
            $arguments = $tmp;
            
            return call_user_func_array([new $name(), $method], $arguments);
        }
        catch (\Exception $e)
        {
            throw new InvalidRouteCallbackException($e->getMessage());
        }
    }

    /**
     * 获取当前要执行的控制器路径
     *
     * @param string $module
     * @return string
     */
    private function getControllerPath($module)
    {
        if (in_array($module, ['', 'default']))
        {
            return App::getFacadeApplication()['path.app'] . '/controllers';
        }
        return App::getFacadeApplication()['path.app'] . '/modules/' . $module . '/controllers';
    }

    /**
     * 标准化名称
     *
     * @param string $name
     * @param string $mthod
     * @return array
     */
    private function normalizeName($name, $mthod)
    {
        return [$name . 'Controller', $mthod . 'Action'];
    }
}