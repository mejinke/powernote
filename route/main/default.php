<?php

use Powernote\Routing\Route;
use Powernote\Support\Facades\Router;

//组路由测试
Router::addGroup('group', [new Route(['GET'], '/g(@id:\d+)(/@name)', 'index/groupRoute')]);


Router::get('/test', function(){
    return 'This "main" App /test route.';
});