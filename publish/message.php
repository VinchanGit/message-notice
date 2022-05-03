<?php

declare(strict_types=1);

return [
    'default' => env('NOTIFY_DEFAULT_CHANNEL', 'mail'),
    'channels' => [
        // 钉钉群机器人
        \MessageNotice\Driver\DingTalk::class => [
            'driver' => 'dingTalk',
            'token' => env('NOTIFY_DINGTALK_TOKEN', ''),
            'secret' => env('NOTIFY_DINGTALK_SECRET', ''),
            'keyword' => env('NOTIFY_DINGTALK_KEYWORD', []),
            'pipeline' => [
                // 业务信息告警群
                'info' => [
                    'token' => env('NOTIFY_DINGTALK_TOKEN', ''),
                    'secret' => env('NOTIFY_DINGTALK_SECRET', ''),
                    'keyword' => env('NOTIFY_DINGTALK_KEYWORD', []),
                ],
                // 错误信息告警群
                'error' => [
                    'token' => env('NOTIFY_DINGTALK_TOKEN', ''),
                    'secret' => env('NOTIFY_DINGTALK_SECRET', ''),
                    'keyword' => env('NOTIFY_DINGTALK_KEYWORD', []),
                ],
            ],
        ],

        // 飞书群机器人
        \MessageNotice\Driver\FeiShu::class => [
            'driver' => 'feiShu',
            'token' => env('NOTIFY_FEISHU_TOKEN', ''),
            'secret' => env('NOTIFY_FEISHU_SECRET', ''),
            'keyword' => env('NOTIFY_FEISHU_KEYWORD'),
            'pipeline' => [
                'info' => [
                    'token' => env('NOTIFY_FEISHU_TOKEN', ''),
                    'secret' => env('NOTIFY_FEISHU_SECRET', ''),
                    'keyword' => env('NOTIFY_FEISHU_KEYWORD'),
                ],
            ],
        ],

        // 邮件
        'mail' => [
            'driver' => 'mail',
            'dsn' => env('NOTIFY_MAIL_DSN'),
            'from' => env('NOTIFY_MAIL_FROM'),
            'to' => env('NOTIFY_MAIL_TO'),
            'pipeline' => [
                'info' => [
                    'dsn' => env('NOTIFY_MAIL_DSN'),
                    'from' => env('NOTIFY_MAIL_FROM'),
                    'to' => env('NOTIFY_MAIL_TO'),
                ],
            ],
        ],

        // 企业微信群机器人
        \MessageNotice\Driver\Wechat::class => [
            'driver' => 'wechat',
            'token' => env('NOTIFY_WECHAT_TOKEN'),
            'pipeline' => [
                'info' => [
                    'token' => env('NOTIFY_WECHAT_TOKEN'),
                ],
            ],
        ],
    ],
];
