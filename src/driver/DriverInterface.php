<?php

declare(strict_types=1);

namespace MessageNotice\driver;

use MessageNotice\Manager;

interface DriverInterface
{
    /**
     * 核心处理.
     * @return mixed
     */
    public function handler(Manager $manager);

    /**
     * 发送请求
     * @return mixed
     */
    public function send();
}
