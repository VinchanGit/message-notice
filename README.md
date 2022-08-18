# message-notice

## `温馨提示：该组件已迁移至：https://github.com/VinchanGit/message-notify 继续维护迭代`

## 功能

* 监控发送应用异常
* 支持多种通道(钉钉群机器人、飞书群机器人、邮件、QQ 频道机器人、企业微信群机器人)
* 支持扩展自定义通道

## 环境要求

* hyperf >= 2.0

## 安装

```bash
composer require vinchan/message-notice -vvv
```

## 配置文件

发布配置文件`config/message.php`

```bash
hyperf vendor:publish vinchan/message-notice
```

`.env` 文件中配置

```dotenv
## dingTalk
NOTIFY_DINGTALK_TOKEN=token
NOTIFY_DINGTALK_SECRET=secret
## feiShu
NOTIFY_FEISHU_TOKEN=token
NOTIFY_FEISHU_SECRET=secret
```

## 使用

```php
$message = make(\MessageNotice\Message::class);
$message->at(['13000000000'])->channel([FeiShu::class,DingTalk::class])->pipeline('info')->content('发送的内容')->send();
```

## 扩展自定义通道

> 文档完善中

## 测试

> 文档完善中

## 未来规划

* 支持自定义数据收集器
* 支持自定义数据转换器

## 协议

MIT 许可证（MIT）。有关更多信息，请参见[协议文件](LICENSE)。