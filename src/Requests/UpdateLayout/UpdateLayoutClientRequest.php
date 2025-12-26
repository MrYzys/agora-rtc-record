<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\UpdateLayout;

use InvalidArgumentException;
use stdClass;
use MrYzys\AgoraRtcRecord\Requests\Layout\BackgroundConfigEntry;
use MrYzys\AgoraRtcRecord\Requests\Layout\LayoutConfigEntry;

class UpdateLayoutClientRequest
{
    private ?string $maxResolutionUid = null;

    private ?int $mixedVideoLayout = null;

    private ?string $backgroundColor = null;

    private ?string $backgroundImage = null;

    private ?string $defaultUserBackgroundImage = null;

    /**
     * @var LayoutConfigEntry[]
     */
    private array $layoutConfig = [];

    /**
     * @var BackgroundConfigEntry[]
     */
    private array $backgroundConfig = [];

    public function setMaxResolutionUid(?string $uid): self
    {
        $this->maxResolutionUid = $uid;
        return $this;
    }

    public function setMixedVideoLayout(?int $layout): self
    {
        $this->mixedVideoLayout = $layout;
        return $this;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        if ($backgroundColor !== null && !preg_match('/^#[0-9a-fA-F]{6}$/', $backgroundColor)) {
            throw new InvalidArgumentException('backgroundColor must be a hex string such as #FF0000');
        }

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
     * @param LayoutConfigEntry[] $entries
     */
    public function setLayoutConfig(array $entries): self
    {
        $this->layoutConfig = array_values($entries);
        return $this;
    }

    public function addLayoutConfig(LayoutConfigEntry $entry): self
    {
        $this->layoutConfig[] = $entry;
        return $this;
    }

    /**
     * @param BackgroundConfigEntry[] $entries
     */
    public function setBackgroundConfig(array $entries): self
    {
        $this->backgroundConfig = array_values($entries);
        return $this;
    }

    public function addBackgroundConfig(BackgroundConfigEntry $entry): self
    {
        $this->backgroundConfig[] = $entry;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [];

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

        if ($this->layoutConfig !== []) {
            $payload['layoutConfig'] = array_map(
                static fn (LayoutConfigEntry $entry) => $entry->toArray(),
                $this->layoutConfig
            );
        }

        if ($this->backgroundConfig !== []) {
            $payload['backgroundConfig'] = array_map(
                static fn (BackgroundConfigEntry $entry) => $entry->toArray(),
                $this->backgroundConfig
            );
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
