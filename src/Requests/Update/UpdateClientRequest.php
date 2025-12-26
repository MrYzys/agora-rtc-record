<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

use stdClass;
use MrYzys\AgoraRtcRecord\Requests\Acquire\StorageConfig;

class UpdateClientRequest
{
    private ?StreamSubscribe $streamSubscribe = null;

    private ?WebRecordingConfig $webRecordingConfig = null;

    private ?RtmpPublishConfig $rtmpPublishConfig = null;

    private ?StorageConfig $storageConfig = null;

    public function setStreamSubscribe(?StreamSubscribe $streamSubscribe): self
    {
        $this->streamSubscribe = $streamSubscribe;
        return $this;
    }

    public function setWebRecordingConfig(?WebRecordingConfig $webRecordingConfig): self
    {
        $this->webRecordingConfig = $webRecordingConfig;
        return $this;
    }

    public function setRtmpPublishConfig(?RtmpPublishConfig $rtmpPublishConfig): self
    {
        $this->rtmpPublishConfig = $rtmpPublishConfig;
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
    public function toArray()
    {
        $payload = [];

        if ($this->streamSubscribe !== null) {
            $value = $this->streamSubscribe->toArray();
            if (!$this->isEmptyStdClass($value)) {
                $payload['streamSubscribe'] = $value;
            }
        }

        if ($this->webRecordingConfig !== null) {
            $value = $this->webRecordingConfig->toArray();
            if (!$this->isEmptyStdClass($value)) {
                $payload['webRecordingConfig'] = $value;
            }
        }

        if ($this->rtmpPublishConfig !== null) {
            $value = $this->rtmpPublishConfig->toArray();
            if (!$this->isEmptyStdClass($value)) {
                $payload['rtmpPublishConfig'] = $value;
            }
        }

        if ($this->storageConfig !== null) {
            $payload['storageConfig'] = $this->storageConfig->toArray();
        }

        return $payload === [] ? new stdClass() : $payload;
    }

    /**
     * @param array<string, mixed>|stdClass $value
     */
    private function isEmptyStdClass($value): bool
    {
        return $value instanceof stdClass && $value == new stdClass();
    }
}
