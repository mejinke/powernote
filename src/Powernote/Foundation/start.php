<?php
use Powernote\Config\Config;
use Powernote\Routing\Automate;
use Powernote\Support\Facades\Router;
use Powernote\Support\Facades\Facade;

// 开启错误报告
error_reporting(E_ALL);

// 加载配置
$app->singleton('config', function ($container) use($app)
{
    return new Config($app->getConfigLoader(), $app['env']);
});

$config = $app['config']['app'];

// 设置APP
Facade::clearResolvedInstances();
Facade::setFacadeApplication($app);

// 安装路由
$app->installRoute($app['path.route']);

// 自动路由
if ($config['route.automate'] == true)
{
    Router::add([new Automate()]);
}

//开启错误提示
if ($config['debug'] == true) 
{
    ini_set('display_errors', 'on');
}

// 设置时区
date_default_timezone_set($config['timezone']);