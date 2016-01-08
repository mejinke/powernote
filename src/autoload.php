<?php
require __DIR__ . '/Powernote/Autoloader/ClassLoader.php';

// 注册类加载器
\Powernote\Autoloader\ClassLoader::init()->register();

// 注册类别名加载器
\Powernote\Autoloader\ClassAliasLoader::init()->register();