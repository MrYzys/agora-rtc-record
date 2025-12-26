<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

class StorageConfig
{
    private int $vendor;

    private int $region;

    private string $bucket;

    private string $accessKey;

    private string $secretKey;

    private ?string $stsToken = null;

    private ?int $stsExpiration = null;

    /**
     * @var string[]|null
     */
    private ?array $fileNamePrefix = null;

    private ?StorageExtensionParams $extensionParams = null;

    public function __construct(int $vendor, int $region, string $bucket, string $accessKey, string $secretKey)
    {
        $this->vendor = $vendor;
        $this->region = $region;
        $this->bucket = $bucket;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function setStsToken(?string $stsToken): self
    {
        $this->stsToken = $stsToken;
        return $this;
    }

    public function setStsExpiration(?int $stsExpiration): self
    {
        $this->stsExpiration = $stsExpiration;
        return $this;
    }

    /**
     * @param string[]|null $fileNamePrefix
     */
    public function setFileNamePrefix(?array $fileNamePrefix): self
    {
        $this->fileNamePrefix = $fileNamePrefix;
        return $this;
    }

    public function setExtensionParams(?StorageExtensionParams $extensionParams): self
    {
        $this->extensionParams = $extensionParams;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'vendor' => $this->vendor,
            'region' => $this->region,
            'bucket' => $this->bucket,
            'accessKey' => $this->accessKey,
            'secretKey' => $this->secretKey,
        ];

        if ($this->stsToken !== null) {
            $payload['stsToken'] = $this->stsToken;
        }

        if ($this->stsExpiration !== null) {
            $payload['stsExpiration'] = $this->stsExpiration;
        }

        if ($this->fileNamePrefix !== null) {
            $payload['fileNamePrefix'] = $this->fileNamePrefix;
        }

        if ($this->extensionParams !== null) {
            $payload['extensionParams'] = $this->extensionParams->toArray();
        }

        return $payload;
    }
}
