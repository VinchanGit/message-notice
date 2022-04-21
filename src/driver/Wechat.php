<?php

declare(strict_types=1);

namespace MessageNotice\driver;

use Hyperf\Contract\ConfigInterface;
use MessageNotice\Manager;
use MessageNotice\Request;
use Psr\Container\ContainerInterface;

class Wechat extends DriverManager implements DriverInterface
{
    protected $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=';

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class)->get('message.channels.' . __CLASS__);
    }

    public function handler(Manager $manager)
    {
        $this->manager = $manager;
        $request = make(Request::class);

        $timestamp = time();
        $request->domain = $this->getDomain();
        $request->json = [
            'timestamp' => $timestamp,
            'msgtype' => 'text',
            'text' => [
                'content' => $this->getContent(),
                'mentioned_mobile_list' => $this->getAt(),
            ],
        ];

        $this->request = $request;
    }

    public function send()
    {
        return $this->request->post();
    }

    private function getAt(): array
    {
        $result = [];
        $at = $this->manager->getAt();
        if ((is_string($at) && $at == 'all') || (is_array($at) && in_array('all', $at))) {
            return ['@all'];
        }

        if (is_array($at)) {
            foreach ($at as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }

    private function getContent(): string
    {
        return $this->manager->getContent();
    }

    private function getDomain(): string
    {
        $token = $this->getConfig('token');
        return "{$this->url}{$token}";
    }
}
