<?php
namespace App\User\Controllers;

use Powernote\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return 'user.index.index';
    }
}