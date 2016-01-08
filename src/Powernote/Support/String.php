<?php
namespace Powernote\Support;

class String
{

    /**
     * 返回字符串的长度
     *
     * @param string $value
     * @return int
     */
    public static function length($value)
    {
        return mb_strlen($value);
    }

    /**
     * 限制字符串显示的字数
     *
     * @param string $value
     * @param int $limit
     * @param string $end
     * @return string
     */
    public static function limit($value, $limit = 100, $end = '...')
    {
        if (mb_strlen($value) <= $limit)
        {
            return $value;
        }
        
        return rtrim(mb_substr($value, 0, $limit, 'UTF-8')) . $end;
    }

    /**
     * 清除javascript代码
     *
     * @param string $value
     * @return string
     */
    public static function clearJavascript($value)
    {
        if (empty($value)) return $value;
        
        $value = trim($value);
        // 完全过滤注释
        $value = preg_replace('/<!--?.*-->/', '', $value);
        // 完全过滤动态代码
        $value = preg_replace('/<\?|\?>/', '', $value);
        // 完全过滤js
        $value = preg_replace('/<script?.*\/script>/is', '', $value);
        // 完全过滤iframe
        $value = preg_replace('/<iframe?.*\/iframe>/is', '', $value);
        // 过滤多余html
        $value = preg_replace('/<\/?(html|head|meta|link|base|body|title|script|form|iframe|frame|frameset)[^><]*>/i', '', $value);
        // 过滤on事件lang js
        while (preg_match('/(<[^><]+)(onclick|onfinish|onmouse|onexit|onerror|onkey|onload|onchange|onfocus|onblur)[^><]+/i', $value, $mat))
        {
            $value = str_replace($mat[0], $mat[1], $value);
        }
        while (preg_match('/(<[^><]+)(window\.|js:|javascript:|about:|file:|document\.|vbs:|vbscript:|cookie)([^><]*)/i', $value, $mat))
        {
            $value = str_replace($mat[0], $mat[1] . $mat[3], $value);
        }
        
        return $value;
    }

    /**
     * 格式化为纯文本
     *
     * @param string $value
     * @return string
     */
    public static function text($value)
    {
        return htmlspecialchars(strip_tags(static::clearJavascript($value)), ENT_NOQUOTES);
    }
}