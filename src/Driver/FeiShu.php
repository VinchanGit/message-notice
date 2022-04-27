<?php

declare(strict_types=1);

namespace MessageNotice\Driver;

use Hyperf\Contract\ConfigInterface;
use MessageNotice\Manager;
use MessageNotice\Request;
use Psr\Container\ContainerInterface;

class FeiShu extends DriverManager implements DriverInterface
{
    protected $url = 'https://open.feishu.cn/open-apis/bot/v2/hook/';

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
            'sign' => $this->getSign($timestamp),
            'msg_type' => 'text',
            'content' => [
                'text' => $this->getContent(),
            ],
        ];

        $this->request = $request;
    }

    public function send()
    {
        return $this->request->post();
    }

    /**
     * 生成签名.
     * @param int $timestamp 时间戳
     */
    private function getSign(int $timestamp): string
    {
        $secret = $this->getConfig('secret');
        $secret = hash_hmac('sha256', '', $timestamp . "\n" . $secret, true);
        return base64_encode($secret);
    }

    /**
     * 生成请求地址
     */
    private function getDomain(): string
    {
        $token = $this->getConfig('token');
        return "{$this->url}/{$token}";
    }

    /**
     * 生成内容.
     */
    private function getContent(): string
    {
        return $this->manager->getContent() . $this->getAt();
    }

    /**
     * 生成@.
     */
    private function getAt(): string
    {
        $result = '';
        $at = $this->manager->getAt();
        if ((is_string($at) && $at == 'all') || (is_array($at) && in_array('all', $at))) {
            return '<at user_id="all">所有人</at>';
        }

        if (is_array($at)) {
            foreach ($at as $item) {
                if (strchr($item, '@') === false) {
                    $result .= '<at phone="' . $item . '">' . $item . '</at>';
                } else {
                    $result .= '<at mail="' . $item . '">' . $item . '</at>';
                }
            }
        }

        return $result;
    }
}
