<?php

declare(strict_types=1);

namespace MessageNotice;

class Manager
{
    /** 被@的人.
     * @var array|string
     */
    public $at;

    /**
     * 发送的内容.
     * @var string
     */
    public $content;

    /**
     * 发送管道.
     */
    private string $pipeline;

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setAt($at)
    {
        $this->at = $at;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAt()
    {
        return $this->at;
    }

    public function setPipeline(string $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function getPipeline(): string
    {
        return $this->pipeline;
    }
}
