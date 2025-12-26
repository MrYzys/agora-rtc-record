<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

use InvalidArgumentException;

class RecordingFileConfig
{
    /**
     * @var string[]|null
     */
    private ?array $avFileTypes = null;

    /**
     * @param string[]|null $avFileTypes
     */
    public function setAvFileTypes(?array $avFileTypes): self
    {
        if ($avFileTypes !== null) {
            foreach ($avFileTypes as $type) {
                if (!is_string($type)) {
                    throw new InvalidArgumentException('avFileTypes must be strings');
                }
            }
        }

        $this->avFileTypes = $avFileTypes;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->avFileTypes !== null) {
            $payload['avFileType'] = $this->avFileTypes;
        }

        return $payload;
    }
}
