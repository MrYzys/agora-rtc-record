<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Stop;

use stdClass;

class StopClientRequest
{
    private ?bool $asyncStop = null;

    public function setAsyncStop(?bool $asyncStop): self
    {
        $this->asyncStop = $asyncStop;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toArray()
    {
        $payload = [];

        if ($this->asyncStop !== null) {
            $payload['async_stop'] = $this->asyncStop;
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
