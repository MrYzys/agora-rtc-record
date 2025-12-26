<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire\Transcoding;

use MrYzys\AgoraRtcRecord\Requests\Layout\BackgroundConfigEntry;
use MrYzys\AgoraRtcRecord\Requests\Layout\LayoutConfigEntry;

class TranscodingConfig
{
    private ?int $width = null;

    private ?int $height = null;

    private ?int $fps = null;

    private ?int $bitrate = null;

    private ?string $maxResolutionUid = null;

    private ?int $mixedVideoLayout = null;

    private ?string $backgroundColor = null;

    private ?string $backgroundImage = null;

    private ?string $defaultUserBackgroundImage = null;

    /**
     * @var LayoutConfigEntry[]
     */
    private array $layoutConfigEntries = [];

    /**
     * @var BackgroundConfigEntry[]
     */
    private array $backgroundConfigEntries = [];

    public function setWidth(?int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function setFps(?int $fps): self
    {
        $this->fps = $fps;
        return $this;
    }

    public function setBitrate(?int $bitrate): self
    {
        $this->bitrate = $bitrate;
        return $this;
    }

    public function setMaxResolutionUid(?string $maxResolutionUid): self
    {
        $this->maxResolutionUid = $maxResolutionUid;
        return $this;
    }

    public function setMixedVideoLayout(?int $mixedVideoLayout): self
    {
        $this->mixedVideoLayout = $mixedVideoLayout;
        return $this;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    public function setBackgroundImage(?string $backgroundImage): self
    {
        $this->backgroundImage = $backgroundImage;
        return $this;
    }

    public function setDefaultUserBackgroundImage(?string $defaultUserBackgroundImage): self
    {
        $this->defaultUserBackgroundImage = $defaultUserBackgroundImage;
        return $this;
    }

    /**
     * @param LayoutConfigEntry[] $layoutConfigEntries
     */
    public function setLayoutConfigEntries(array $layoutConfigEntries): self
    {
        $this->layoutConfigEntries = array_values($layoutConfigEntries);
        return $this;
    }

    public function addLayoutConfigEntry(LayoutConfigEntry $entry): self
    {
        $this->layoutConfigEntries[] = $entry;
        return $this;
    }

    /**
     * @param BackgroundConfigEntry[] $backgroundConfigEntries
     */
    public function setBackgroundConfigEntries(array $backgroundConfigEntries): self
    {
        $this->backgroundConfigEntries = array_values($backgroundConfigEntries);
        return $this;
    }

    public function addBackgroundConfigEntry(BackgroundConfigEntry $entry): self
    {
        $this->backgroundConfigEntries[] = $entry;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

        if ($this->width !== null) {
            $payload['width'] = $this->width;
        }

        if ($this->height !== null) {
            $payload['height'] = $this->height;
        }

        if ($this->fps !== null) {
            $payload['fps'] = $this->fps;
        }

        if ($this->bitrate !== null) {
            $payload['bitrate'] = $this->bitrate;
        }

        if ($this->maxResolutionUid !== null) {
            $payload['maxResolutionUid'] = $this->maxResolutionUid;
        }

        if ($this->mixedVideoLayout !== null) {
            $payload['mixedVideoLayout'] = $this->mixedVideoLayout;
        }

        if ($this->backgroundColor !== null) {
            $payload['backgroundColor'] = $this->backgroundColor;
        }

        if ($this->backgroundImage !== null) {
            $payload['backgroundImage'] = $this->backgroundImage;
        }

        if ($this->defaultUserBackgroundImage !== null) {
            $payload['defaultUserBackgroundImage'] = $this->defaultUserBackgroundImage;
        }

        if ($this->layoutConfigEntries !== []) {
            $payload['layoutConfig'] = array_map(
                static fn (LayoutConfigEntry $entry) => $entry->toArray(),
                $this->layoutConfigEntries
            );
        }

        if ($this->backgroundConfigEntries !== []) {
            $payload['backgroundConfig'] = array_map(
                static fn (BackgroundConfigEntry $entry) => $entry->toArray(),
                $this->backgroundConfigEntries
            );
        }

        return $payload;
    }
}
