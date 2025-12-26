<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

class WebRecorderServiceParam implements ServiceParamInterface
{
    private string $url;

    private int $audioProfile;

    private int $videoWidth;

    private int $videoHeight;

    private int $maxRecordingHour;

    private ?int $videoBitrate = null;

    private ?int $videoFps = null;

    private ?bool $mobile = null;

    private ?int $maxVideoDuration = null;

    private ?bool $onhold = null;

    private ?int $readyTimeout = null;

    public function __construct(
        string $url,
        int $audioProfile,
        int $videoWidth,
        int $videoHeight,
        int $maxRecordingHour
    ) {
        $this->url = $url;
        $this->audioProfile = $audioProfile;
        $this->videoWidth = $videoWidth;
        $this->videoHeight = $videoHeight;
        $this->maxRecordingHour = $maxRecordingHour;
    }

    public function setVideoBitrate(?int $videoBitrate): self
    {
        $this->videoBitrate = $videoBitrate;
        return $this;
    }

    public function setVideoFps(?int $videoFps): self
    {
        $this->videoFps = $videoFps;
        return $this;
    }

    public function setMobile(?bool $mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function setMaxVideoDuration(?int $maxVideoDuration): self
    {
        $this->maxVideoDuration = $maxVideoDuration;
        return $this;
    }

    public function setOnhold(?bool $onhold): self
    {
        $this->onhold = $onhold;
        return $this;
    }

    public function setReadyTimeout(?int $readyTimeout): self
    {
        $this->readyTimeout = $readyTimeout;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'url' => $this->url,
            'audioProfile' => $this->audioProfile,
            'videoWidth' => $this->videoWidth,
            'videoHeight' => $this->videoHeight,
            'maxRecordingHour' => $this->maxRecordingHour,
        ];

        if ($this->videoBitrate !== null) {
            $payload['videoBitrate'] = $this->videoBitrate;
        }

        if ($this->videoFps !== null) {
            $payload['videoFps'] = $this->videoFps;
        }

        if ($this->mobile !== null) {
            $payload['mobile'] = $this->mobile;
        }

        if ($this->maxVideoDuration !== null) {
            $payload['maxVideoDuration'] = $this->maxVideoDuration;
        }

        if ($this->onhold !== null) {
            $payload['onhold'] = $this->onhold;
        }

        if ($this->readyTimeout !== null) {
            $payload['readyTimeout'] = $this->readyTimeout;
        }

        return $payload;
    }
}
