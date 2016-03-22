<?php
namespace App\Main\Controllers;

use Powernote\Controller\Controller;
use Powernote\Database\Connection\Connection;

class IndexController extends Controller
{

    public function indexAction($type)
    {
        (new Connection());
        return 'Hello,Powernote.' . $type;
    }

    public function showAction($name)
    {
        return 'showAction name=' . $name;
    }

    public function groupRouteAction($id, $name)
    {
        return 'Group Router Test ID:'.$id.' Name:'.$name;
    }
}