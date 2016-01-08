<?php

class IndexController 
{
    public function indexAction($name, $id)
    {
        return 'user.index'.$id.' name:'.$name;
    }
    
    public function showAction()
    {
        return 'show';
    }
}