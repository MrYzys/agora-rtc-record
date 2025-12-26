<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

class RtmpPublishOutput
{
    private ?string $rtmpUrl = null;

    public function __construct(?string $rtmpUrl = null)
    {
        $this->rtmpUrl = $rtmpUrl;
    }

    public function setRtmpUrl(?string $rtmpUrl): self
    {
        $this->rtmpUrl = $rtmpUrl;
        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->rtmpUrl !== null) {
            $payload['rtmpUrl'] = $this->rtmpUrl;
        }

        return $payload;
    }
}
