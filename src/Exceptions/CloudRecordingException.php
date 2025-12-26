<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Exceptions;

use RuntimeException;

/**
 * Generic exception wrapper for Agora/Shengwang cloud recording API failures.
 */
class CloudRecordingException extends RuntimeException
{
    private ?array $responsePayload;

    public function __construct(string $message, int $code = 0, ?array $responsePayload = null)
    {
        parent::__construct($message, $code);
        $this->responsePayload = $responsePayload;
    }

    public function getResponsePayload(): ?array
    {
        return $this->responsePayload;
    }
}
