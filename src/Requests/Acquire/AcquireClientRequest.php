<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;

/**
 * Container for the nested clientRequest block of the acquire call.
 */
class AcquireClientRequest
{
    private ?int $scene = null;

    private ?int $resourceExpiredHour = null;

    /**
     * @var string[]|null
     */
    private ?array $excludeResourceIds = null;

    private ?int $regionAffinity = null;

    private ?StartParameter $startParameter = null;

    private ?RecordingConfig $recordingConfig = null;

    public function setScene(?int $scene): self
    {
        $this->scene = $scene;
        return $this;
    }

    public function setResourceExpiredHour(?int $hours): self
    {
        $this->resourceExpiredHour = $hours;
        return $this;
    }

    /**
     * @param string[]|null $resourceIds
     */
    public function setExcludeResourceIds(?array $resourceIds): self
    {
        $this->excludeResourceIds = $resourceIds;
        return $this;
    }

    public function setRegionAffinity(?int $regionAffinity): self
    {
        $this->regionAffinity = $regionAffinity;
        return $this;
    }

    public function setStartParameter(?StartParameter $startParameter): self
    {
        $this->startParameter = $startParameter;
        return $this;
    }

    public function setRecordingConfig(?RecordingConfig $recordingConfig): self
    {
        $this->recordingConfig = $recordingConfig;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toPayload()
    {
        $payload = [];

        if ($this->scene !== null) {
            $payload['scene'] = $this->scene;
        }

        if ($this->resourceExpiredHour !== null) {
            $payload['resourceExpiredHour'] = $this->resourceExpiredHour;
        }

        if ($this->excludeResourceIds !== null) {
            $payload['excludeResourceIds'] = $this->excludeResourceIds;
        }

        if ($this->regionAffinity !== null) {
            $payload['regionAffinity'] = $this->regionAffinity;
        }

        if ($this->startParameter !== null) {
            $payload['startParameter'] = $this->startParameter->toPayload();
        }

        if ($this->recordingConfig !== null) {
            $payload['recordingConfig'] = $this->recordingConfig->toPayload();
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
