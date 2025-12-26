<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Layout;

class LayoutConfigEntry
{
    private ?string $uid;

    private float $xAxis;

    private float $yAxis;

    private float $width;

    private float $height;

    private ?float $alpha = null;

    private ?int $renderMode = null;

    public function __construct(?string $uid, float $xAxis, float $yAxis, float $width, float $height)
    {
        $this->uid = $uid;
        $this->xAxis = $xAxis;
        $this->yAxis = $yAxis;
        $this->width = $width;
        $this->height = $height;
    }

    public function setAlpha(?float $alpha): self
    {
        $this->alpha = $alpha;
        return $this;
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
            'x_axis' => $this->xAxis,
            'y_axis' => $this->yAxis,
            'width' => $this->width,
            'height' => $this->height,
        ];

        if ($this->uid !== null) {
            $payload['uid'] = $this->uid;
        }

        if ($this->alpha !== null) {
            $payload['alpha'] = $this->alpha;
        }

        if ($this->renderMode !== null) {
            $payload['render_mode'] = $this->renderMode;
        }

        return $payload;
    }
}
