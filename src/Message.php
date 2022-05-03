<?php

declare(strict_types=1);

namespace MessageNotice;

use MessageNotice\Driver\DriverInterface;
use MessageNotice\Exception\MessageException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Message
{
    public Manager $manager;

    public $channel;

    protected ContainerInterface $container;

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
        } catch (\Throwable $e) {
            throw new MessageException($e->getMessage(), $e->getCode());
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
                /* @var DriverInterface $channel */
                $channel->send();
            } catch (\Throwable $e) {
                throw new MessageException($e->getMessage(), $e->getCode());
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
     * 管道设置.
     * @return Message
     */
    public function pipeline(string $pipeline = '')
    {
        if (! empty($pipeline)) {
            $this->manager->setPipeline($pipeline);
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
