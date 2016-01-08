<?php
namespace Powernote\Support;

use Powernote\Autoloader\ClassLoader;
use Powernote\Autoloader\ClassAliasLoader;

class Di
{
    
    public function set($name, $concrete)
    {
        
    }
    
    protected function getClosure($concrete)
    {
        //是否为一个类字符串定义
        if (is_string($concrete)) 
        {
            //类别名
            if (ClassAliasLoader::init()->has($concrete)) 
            {
                return function() use($concrete)
                {
                    
                };
            }
            if ($r == false)
            ClassLoader::init()->register()->load($concrete);
        }
        
    }
}