<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

class UpdateRequest
{
    private string $resourceId;

    private string $sid;

    private string $mode;

    private string $channelName;

    private string $uid;

    private UpdateClientRequest $clientRequest;

    public function __construct(
        string $resourceId,
        string $sid,
        string $mode,
        string $channelName,
        string $uid,
        UpdateClientRequest $clientRequest
    ) {
        $this->resourceId = $resourceId;
        $this->sid = $sid;
        $this->mode = $mode;
        $this->channelName = $channelName;
        $this->uid = $uid;
        $this->clientRequest = $clientRequest;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getSid(): string
    {
        return $this->sid;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'cname' => $this->channelName,
            'uid' => $this->uid,
            'clientRequest' => $this->clientRequest->toArray(),
        ];
    }
}
