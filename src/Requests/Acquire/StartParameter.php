<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;

class StartParameter
{
    private ?string $token = null;

    private ?StorageConfig $storageConfig = null;

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function setStorageConfig(?StorageConfig $storageConfig): self
    {
        $this->storageConfig = $storageConfig;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toPayload()
    {
        $payload = [];

        if ($this->token !== null) {
            $payload['token'] = $this->token;
        }

        if ($this->storageConfig !== null) {
            $payload['storageConfig'] = $this->storageConfig->toArray();
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
