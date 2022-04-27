<?php

declare(strict_types=1);

namespace MessageNotice\Driver;

use MessageNotice\Manager;
use MessageNotice\Request;

class DriverManager
{
    /**
     * 请求对象
     * @var Request
     */
    public $request;

    /**
     * 配置项.
     * @var array
     */
    public $config;

    /**
     * 请求地址
     * @var string
     */
    protected $url;

    /**
     * 内容值对象
     * @var Manager
     */
    protected $manager;

    public function getConfig(string $key = '')
    {
        if (empty($key)) {
            return $this->config;
        }

        return $this->config[$key] ?? $this->config;
    }
}
