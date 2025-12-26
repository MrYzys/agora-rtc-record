<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Query;

class QueryRequest
{
    private string $resourceId;

    private string $sid;

    private string $mode;

    public function __construct(string $resourceId, string $sid, string $mode)
    {
        $this->resourceId = $resourceId;
        $this->sid = $sid;
        $this->mode = $mode;
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
}
