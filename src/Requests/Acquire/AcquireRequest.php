<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

/**
 * Value object describing the payload of the acquire call.
 */
class AcquireRequest
{
    private string $channelName;

    private string $uid;

    private AcquireClientRequest $clientRequest;

    public function __construct(string $channelName, string $uid, ?AcquireClientRequest $clientRequest = null)
    {
        $this->channelName = $channelName;
        $this->uid = $uid;
        $this->clientRequest = $clientRequest ?? new AcquireClientRequest();
    }

    /**
     * Export the request into the Agora REST payload shape.
     *
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
