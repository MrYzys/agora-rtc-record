<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

interface ServiceParamInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
