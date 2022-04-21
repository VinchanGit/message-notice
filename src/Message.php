<?php

declare(strict_types=1);

namespace MessageNotice;

use MessageNotice\driver\DriverInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Message
{
    public Manager $manager;

    protected ContainerInterface $container;

    public $channel;

    public function __construct(ContainerInterface $container, Manager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    public function send()
    {
        try {
            $this->beforeSend();
            $this->do();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 前置操作.
     */
    public function beforeSend()
    {
        foreach ($this->channel as &$channel) {
            /* @var DriverInterface $channel */
            $channel->handler($this->manager);
        }
    }

    /**
     * 执行发送
     */
    public function do()
    {
        foreach ($this->channel as $channel) {
            try {
                /** @var DriverInterface $channel */
                echo $channel->send();
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    /**
     * 通道配置.
     * @param $channels
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return $this
     */
    public function channel($channels): Message
    {
        foreach ($channels as $channel) {
            $this->channel[$channel] = $this->container->get($channel);
        }

        return $this;
    }

    /**
     * 发送内容.
     */
    public function content(string $content): Message
    {
        $this->manager->setContent($content);
        return $this;
    }

    /**
     * 风格配置.
     * @param mixed $at
     */
    public function at($at): Message
    {
        $this->manager->setAt($at);
        return $this;
    }
}
