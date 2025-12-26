<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Layout;

class BackgroundConfigEntry
{
    private string $uid;

    private string $imageUrl;

    private ?int $renderMode = null;

    public function __construct(string $uid, string $imageUrl)
    {
        $this->uid = $uid;
        $this->imageUrl = $imageUrl;
    }

    public function setRenderMode(?int $renderMode): self
    {
        $this->renderMode = $renderMode;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'uid' => $this->uid,
            'image_url' => $this->imageUrl,
        ];

        if ($this->renderMode !== null) {
            $payload['render_mode'] = $this->renderMode;
        }

        return $payload;
    }
}
