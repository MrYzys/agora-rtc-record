<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

use stdClass;

class WebRecordingConfig
{
    private ?bool $onhold = null;

    public function setOnhold(?bool $onhold): self
    {
        $this->onhold = $onhold;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toArray()
    {
        if ($this->onhold === null) {
            return new stdClass();
        }

        return ['onhold' => $this->onhold];
    }
}
