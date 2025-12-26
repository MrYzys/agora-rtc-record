<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

/**
 * Describes the path/body parameters required by the start API.
 */
class StartRequest
{
    private string $resourceId;

    private string $mode;

    private string $channelName;

    private string $uid;

    private StartClientRequest $clientRequest;

    public function __construct(
        string $resourceId,
        string $mode,
        string $channelName,
        string $uid,
        StartClientRequest $clientRequest
    ) {
        $this->resourceId = $resourceId;
        $this->mode = $mode;
        $this->channelName = $channelName;
        $this->uid = $uid;
        $this->clientRequest = $clientRequest;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
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
            'clientRequest' => $this->clientRequest->toPayload(),
        ];
    }
}
