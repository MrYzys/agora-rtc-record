<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;

/**
 * startParameter 配置，用于在 acquire 阶段预配置 start 参数
 */
class StartParameter
{
    /**
     * 用于鉴权的动态密钥（Token）。
     * 如果项目已启用 App 证书，则必须传入该字段
     */
    private ?string $token = null;

    /**
     * 第三方云存储的配置项
     */
    private StorageConfig $storageConfig;

    public function __construct(StorageConfig $storageConfig, ?string $token = null)
    {
        $this->storageConfig = $storageConfig;
        $this->token = $token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toPayload()
    {
        $payload = [
            'storageConfig' => $this->storageConfig->toArray(),
        ];

        if ($this->token !== null) {
            $payload['token'] = $this->token;
        }

        return $payload;
    }
}
