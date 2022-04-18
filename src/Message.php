<?php

declare(strict_types=1);

namespace MessageNotice;

use Psr\Container\ContainerInterface;

class Message
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var
     */
    protected $channel;

    public function __construct(ContainerInterface $container, Manager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }

    public function send()
    {
        try {
            $this->beforSend();

            $this->do();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function beforSend()
    {
        foreach ($this->channel as &$channel) {
            $channel->handler($this->manager);
        }
    }

    public function do()
    {
        foreach ($this->channel as $channel) {
            try {
                echo $channel->send();
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    /**
     * 通道配置.
     * @param mixed $channels
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
