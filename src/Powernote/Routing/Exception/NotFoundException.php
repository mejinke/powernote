<?php
namespace Powernote\Routing\Exception;

class NotFoundException extends \Exception
{
    protected $code = 404;
}