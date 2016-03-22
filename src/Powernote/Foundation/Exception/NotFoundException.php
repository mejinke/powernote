<?php
namespace Powernote\Foundation\Exception;

class NotFoundException extends \Exception
{
    protected $code = 404;
}