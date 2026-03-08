<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;

/**
 * 第三方云存储扩展参数
 */
class StorageExtensionParams
{
    /**
     * 服务端加密配置
     */
    private ?string $sse = null;

    /**
     * 标签
     */
    private ?string $tag = null;

    /**
     * S3 协议云存储的域名
     * 当 vendor=11（其他 S3 协议云存储）时需要设置
     */
    private ?string $endpoint = null;

    public function setSse(?string $sse): self
    {
        $this->sse = $sse;
        return $this;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function setEndpoint(?string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @return array<string, string>|stdClass
     */
    public function toArray()
    {
        $payload = [];

        if ($this->sse !== null) {
            $payload['sse'] = $this->sse;
        }

        if ($this->tag !== null) {
            $payload['tag'] = $this->tag;
        }

        if ($this->endpoint !== null) {
            $payload['endpoint'] = $this->endpoint;
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
