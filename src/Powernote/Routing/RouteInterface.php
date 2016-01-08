<?php
namespace Powernote\Routing;

/**
 * 路由器接口
 *
 * @author tian <mejinke@gmail.com>
 */
interface RouteInterface
{

    /**
     * 匹配
     *
     * @param \Powernote\Net\Request $request
     * @return bool 是否匹配成功？
     */
    public function match(\Powernote\Net\Request $request);

    /**
     * 响应结果
     * 
     * @return \Powernote\Net\Response
     */
    public function response();
}