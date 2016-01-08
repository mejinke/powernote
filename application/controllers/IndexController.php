<?php
use Powernote\Controller\Controller;

class IndexController extends Controller
{

    public function indexAction($type)
    {
        return 'Hello,Powernote.' . $type;
    }

    public function showAction($name)
    {
        return 'showAction name=' . $name;
    }
}