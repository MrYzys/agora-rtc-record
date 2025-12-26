<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

use stdClass;
use MrYzys\AgoraRtcRecord\Requests\Acquire\RecordingConfig;
use MrYzys\AgoraRtcRecord\Requests\Acquire\StorageConfig;

class StartClientRequest
{
    private ?string $token = null;

    private StorageConfig $storageConfig;

    private ?RecordingConfig $recordingConfig = null;

    private ?RecordingFileConfig $recordingFileConfig = null;

    private ?SnapshotConfig $snapshotConfig = null;

    private ?ExtensionServiceConfig $extensionServiceConfig = null;

    public function __construct(StorageConfig $storageConfig)
    {
        $this->storageConfig = $storageConfig;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function setRecordingConfig(?RecordingConfig $recordingConfig): self
    {
        $this->recordingConfig = $recordingConfig;
        return $this;
    }

    public function setRecordingFileConfig(?RecordingFileConfig $recordingFileConfig): self
    {
        $this->recordingFileConfig = $recordingFileConfig;
        return $this;
    }

    public function setSnapshotConfig(?SnapshotConfig $snapshotConfig): self
    {
        $this->snapshotConfig = $snapshotConfig;
        return $this;
    }

    public function setExtensionServiceConfig(?ExtensionServiceConfig $extensionServiceConfig): self
    {
        $this->extensionServiceConfig = $extensionServiceConfig;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        $payload = [
            'storageConfig' => $this->storageConfig->toArray(),
        ];

        if ($this->token !== null) {
            $payload['token'] = $this->token;
        }

        if ($this->recordingConfig !== null) {
            $payload['recordingConfig'] = $this->recordingConfig->toPayload();
        }

        if ($this->recordingFileConfig !== null) {
            $config = $this->recordingFileConfig->toArray();
            if ($config !== []) {
                $payload['recordingFileConfig'] = $config;
            }
        }

        if ($this->snapshotConfig !== null) {
            $payload['snapshotConfig'] = $this->snapshotConfig->toArray();
        }

        if ($this->extensionServiceConfig !== null) {
            $payload['extensionServiceConfig'] = $this->extensionServiceConfig->toArray();
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
