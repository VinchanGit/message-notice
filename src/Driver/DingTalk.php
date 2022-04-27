<?php

declare(strict_types=1);

namespace MessageNotice\Driver;

use Hyperf\Contract\ConfigInterface;
use MessageNotice\Manager;
use MessageNotice\Request;
use Psr\Container\ContainerInterface;

class DingTalk extends DriverManager implements DriverInterface
{
    protected $url = 'https://oapi.dingtalk.com/robot/send';

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class)->get('message.channels.' . __CLASS__);
    }

    public function handler(Manager $manager)
    {
        $this->manager = $manager;
        $request = make(Request::class);

        $request->domain = $this->getDomain();
        $request->json = [
            'msgtype' => 'text',
            'text' => ['content' => $manager->getContent()],
            'at' => $this->getAt(),
        ];

        $this->request = $request;
    }

    public function send()
    {
        return $this->request->post();
    }

    /**
     * 生成请求地址
     */
    private function getDomain(): string
    {
        $time = time() * 1000;
        $token = $this->getConfig('token');
        $secret = $this->getConfig('secret');
        $secret = hash_hmac('sha256', $time . "\n" . $secret, $secret, true);
        $sign = urlencode(base64_encode($secret));
        return "{$this->url}?access_token={$token}&timestamp={$time}&sign={$sign}";
    }

    /**
     * 生成@.
     * @return array|array[]|bool[]
     */
    private function getAt(): array
    {
        $result = [];
        $at = $this->manager->getAt();
        if ((is_string($at) && $at == 'all') || (is_array($at) && in_array('all', $at))) {
            return [
                'isAtAll' => true,
            ];
        }

        if (is_array($at)) {
            $result = [
                'atMobiles' => $at,
            ];
        }

        return $result;
    }
}
