<?php
namespace Powernote\Support\Validate;

/**
 * 内容验证通用接口
 * 各类验证，包括：手机号、邮箱、身份证号、用户有效性等等
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
interface ValidateInterface
{

    /**
     * 验证
     *
     * @param mixed $mixed 要验证的内容
     * @return bool 是否验证通过？true:通过 false:失败
     */
    public static function validate($mixed);
}