<?php
require __DIR__ . '/autoload.php';

$app = new Powernote\Foundation\Application();
// 检测当前运行环境
$locals = [
    'tians-MacBook-Pro.local', 
    '66c9573fc2a5'
];
$app->detectEnvironment([
    'local' => $locals
]);

// 安装路径
$app->installPaths(require __DIR__ . '/path.php');

// 加载框架启动脚本
require $app['path'] . '/src/Powernote/Foundation/start.php';

return $app;