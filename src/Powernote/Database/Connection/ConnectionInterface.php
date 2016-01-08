<?php
namespace Powernote\Database\Connection;

/**
 * 数据库连接接口
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
interface ConnectionInterface
{

    public function select();

    public function insert();

    public function update();

    public function delete();
    
    public function commit();
}