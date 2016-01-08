<?php

use Powernote\Routing\Route;
use Powernote\Support\Facades\Router;


Router::addGroup('/group2//', [new Route(['GET'], '/group(@id:\d+)(/@name)', 'User/index/index')]);

