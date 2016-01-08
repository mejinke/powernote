<?php
namespace Powernote\Foundation;

/**
 * 检测当前运行环境
 *
 * @author tian <mejinke@gmail.com>
 *        
 */
class EnvironmentDetect
{

    /**
     * 运行环境
     *
     * @var string
     */
    protected $env;

    /**
     * 环境列表
     * 
     * @var array
     */
    protected $environments;

    /**
     * 命令行参数列表
     * 
     * @var array
     */
    protected $argv;

    public function __construct(array $environments, array $argv = null)
    {
        $this->environments = $environments;
        $this->argv = $argv;
    }

    /**
     * 检测运行环境
     *
     * @return string
     */
    public function detect()
    {
        if ($this->env !== null)
        {
            return $this->env;
        }
        
        if ($this->argv !== null)
        {
            return $this->detectConsoleEnvironment();
        }
        
        return $this->detectWebEnvironment();
    }

    /**
     * 检测命令行运行环境
     *
     * @return string
     */
    public function detectConsoleEnvironment()
    {
        $env = 'local';
        $argv = array_slice($this->argv, 1);
        foreach ($argv as $v)
        {
            if (strpos($v, '--env=') === 0)
            {
                $env = explode('--env=', $v)[1];
            }
        }
        
        return $this->env = $env;
    }

    /**
     * 检测web运行环境
     *
     * @return string
     */
    public function detectWebEnvironment()
    {
        foreach ($this->environments as $environment => $hosts)
        {
            foreach ((array) $hosts as $host)
            {
                if (gethostname() == $host)
                {
                    return $this->env = $environment;
                }
            }
        }
        
        return 'production';
    }
}