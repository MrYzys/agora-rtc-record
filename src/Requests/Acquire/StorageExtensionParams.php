<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;

class StorageExtensionParams
{
    private ?string $sse = null;

    private ?string $tag = null;

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
