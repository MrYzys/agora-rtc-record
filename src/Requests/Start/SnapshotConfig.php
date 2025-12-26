<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

use InvalidArgumentException;
use stdClass;

class SnapshotConfig
{
    private ?int $captureInterval = null;

    /**
     * @var string[]|null
     */
    private ?array $fileTypes = null;

    public function setCaptureInterval(?int $captureInterval): self
    {
        if ($captureInterval !== null && $captureInterval <= 0) {
            throw new InvalidArgumentException('captureInterval must be positive');
        }

        $this->captureInterval = $captureInterval;
        return $this;
    }

    /**
     * @param string[]|null $fileTypes
     */
    public function setFileTypes(?array $fileTypes): self
    {
        if ($fileTypes !== null) {
            foreach ($fileTypes as $type) {
                if (!is_string($type)) {
                    throw new InvalidArgumentException('fileTypes must be strings');
                }
            }
        }

        $this->fileTypes = $fileTypes;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toArray()
    {
        $payload = [];

        if ($this->captureInterval !== null) {
            $payload['captureInterval'] = $this->captureInterval;
        }

        if ($this->fileTypes !== null) {
            $payload['fileType'] = $this->fileTypes;
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
