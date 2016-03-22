<?php
namespace Powernote\Database\Connection;

class Connection implements ConnectionInterface
{
    private $cfg;
    
    public function __construct()
    {
        print_r(\Powernote\Support\Facades\App::getFacadeApplication()['config']['database']);
    }
}