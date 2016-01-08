<?php
namespace Powernote\Filesystem;

/**
 * 基础文件系统操作类
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class Filesystem
{

    /**
     * 路径是否存在
     *
     * @param string $path 路径
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * 获取文件内容
     *
     * @param string $path 路径
     * @return string
     *
     * @throws FileNotFoundException
     */
    public function get($path)
    {
        if ($this->isFile($path)) return file_get_contents($path);
      
        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * 是否为一个文件
     *
     * @param string $file 文件地址
     * @return bool
     */
    public function isFile($file)
    {
        return is_file($file);
    }

    /**
     * 是否为一个目录
     *
     * @param string $directory 目录
     * @return bool
     */
    public function isDirectory($directory)
    {
        return is_dir($directory);
    }

    /**
     * 目录是否可写
     *
     * @param string $path 目录
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * 获取一个目录下的所有文件
     *
     * @param string $directory
     * @return array
     */
    public function files($directory, $suffix)
    {
        $glob = glob($directory . '/*');
        if ($glob === false) return [];
        
        return array_filter($glob, function ($file)
        {
            return filetype($file) == 'file';
        });
    }

    /**
     * 引用一个文件，并返回
     *
     * @param string $path
     * @return mixed
     */
    public function getRequire($path)
    {
        if ($this->exists($path))
        {
            return require $path;
        }
    }

    /**
     * 将内容写入到文件
     *
     * @param string $path 路径
     * @param string $contents 内容
     * @return int
     */
    public function put($path, $contents)
    {
        return file_put_contents($path, $contents);
    }

    /**
     * 将数据添加到文件头部
     *
     * @param string $path 路径
     * @param string $data 数据
     * @return int
     */
    public function prepend($path, $data)
    {
        if ($this->exists($path))
        {
            return $this->put($path, $data . $this->get($path));
        }
        return $this->put($path, $data);
    }

    /**
     * 将数据添加到文件末尾
     *
     * @param string $path 路径
     * @param string $data 数据
     * @return int
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * 删除指定目录上的文件
     *
     * @param string|array $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        $success = true;
        foreach ($paths as $path)
        {
            if (! @unlink($path))
            {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * 将一个文件移动一个新的位置
     *
     * @param string $path 路径
     * @param string $target 新路径
     * @return bool
     */
    public function move($path, $target)
    {
        return rename($path, $target);
    }

    /**
     * 将一个文件复制到一个新的位置
     *
     * @param string $path 路径
     * @param string $target 新路径
     * @return bool
     */
    public function copy($path, $target)
    {
        return copy($path, $target);
    }

    /**
     * 获取扩展名
     *
     * @param string $path 路径
     * @return string
     */
    public function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * 递归的创建目录
     *
     * @param string $path 路径
     * @param int $mode 模式
     * @return bool
     */
    public function mkdirs($path, $mode = 0775)
    {
        is_dir(dirname($path)) || $this->mkdirs($path, $mode);
        return is_dir($path) || mkdir($path, $mode);
    }
}