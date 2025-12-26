<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;
use MrYzys\AgoraRtcRecord\Requests\Acquire\Transcoding\TranscodingConfig;

class RecordingConfig
{
    private ?int $channelType = null;

    private ?int $decryptionMode = null;

    private ?string $secret = null;

    private ?string $salt = null;

    private ?int $maxIdleTime = null;

    private ?int $streamTypes = null;

    private ?int $videoStreamType = null;

    /**
     * @var string[]|null
     */
    private ?array $subscribeAudioUids = null;

    /**
     * @var string[]|null
     */
    private ?array $unSubscribeAudioUids = null;

    /**
     * @var string[]|null
     */
    private ?array $subscribeVideoUids = null;

    /**
     * @var string[]|null
     */
    private ?array $unSubscribeVideoUids = null;

    private ?int $subscribeUidGroup = null;

    private ?string $streamMode = null;

    private ?int $audioProfile = null;

    private ?TranscodingConfig $transcodingConfig = null;

    public function setChannelType(?int $channelType): self
    {
        $this->channelType = $channelType;
        return $this;
    }

    public function setDecryptionMode(?int $decryptionMode): self
    {
        $this->decryptionMode = $decryptionMode;
        return $this;
    }

    public function setSecret(?string $secret): self
    {
        $this->secret = $secret;
        return $this;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }

    public function setMaxIdleTime(?int $maxIdleTime): self
    {
        $this->maxIdleTime = $maxIdleTime;
        return $this;
    }

    public function setStreamTypes(?int $streamTypes): self
    {
        $this->streamTypes = $streamTypes;
        return $this;
    }

    public function setVideoStreamType(?int $videoStreamType): self
    {
        $this->videoStreamType = $videoStreamType;
        return $this;
    }

    /**
     * @param string[]|null $uids
     */
    public function setSubscribeAudioUids(?array $uids): self
    {
        $this->subscribeAudioUids = $uids;
        return $this;
    }

    /**
     * @param string[]|null $uids
     */
    public function setUnSubscribeAudioUids(?array $uids): self
    {
        $this->unSubscribeAudioUids = $uids;
        return $this;
    }

    /**
     * @param string[]|null $uids
     */
    public function setSubscribeVideoUids(?array $uids): self
    {
        $this->subscribeVideoUids = $uids;
        return $this;
    }

    /**
     * @param string[]|null $uids
     */
    public function setUnSubscribeVideoUids(?array $uids): self
    {
        $this->unSubscribeVideoUids = $uids;
        return $this;
    }

    public function setSubscribeUidGroup(?int $subscribeUidGroup): self
    {
        $this->subscribeUidGroup = $subscribeUidGroup;
        return $this;
    }

    public function setStreamMode(?string $streamMode): self
    {
        $this->streamMode = $streamMode;
        return $this;
    }

    public function setAudioProfile(?int $audioProfile): self
    {
        $this->audioProfile = $audioProfile;
        return $this;
    }

    public function setTranscodingConfig(?TranscodingConfig $transcodingConfig): self
    {
        $this->transcodingConfig = $transcodingConfig;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toPayload()
    {
        $payload = [];

        if ($this->channelType !== null) {
            $payload['channelType'] = $this->channelType;
        }

        if ($this->decryptionMode !== null) {
            $payload['decryptionMode'] = $this->decryptionMode;
        }

        if ($this->secret !== null) {
            $payload['secret'] = $this->secret;
        }

        if ($this->salt !== null) {
            $payload['salt'] = $this->salt;
        }

        if ($this->maxIdleTime !== null) {
            $payload['maxIdleTime'] = $this->maxIdleTime;
        }

        if ($this->streamTypes !== null) {
            $payload['streamTypes'] = $this->streamTypes;
        }

        if ($this->videoStreamType !== null) {
            $payload['videoStreamType'] = $this->videoStreamType;
        }

        if ($this->subscribeAudioUids !== null) {
            $payload['subscribeAudioUids'] = $this->subscribeAudioUids;
        }

        if ($this->unSubscribeAudioUids !== null) {
            $payload['unSubscribeAudioUids'] = $this->unSubscribeAudioUids;
        }

        if ($this->subscribeVideoUids !== null) {
            $payload['subscribeVideoUids'] = $this->subscribeVideoUids;
        }

        if ($this->unSubscribeVideoUids !== null) {
            $payload['unSubscribeVideoUids'] = $this->unSubscribeVideoUids;
        }

        if ($this->subscribeUidGroup !== null) {
            $payload['subscribeUidGroup'] = $this->subscribeUidGroup;
        }

        if ($this->streamMode !== null) {
            $payload['streamMode'] = $this->streamMode;
        }

        if ($this->audioProfile !== null) {
            $payload['audioProfile'] = $this->audioProfile;
        }

        if ($this->transcodingConfig !== null) {
            $payload['transcodingConfig'] = $this->transcodingConfig->toArray();
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
