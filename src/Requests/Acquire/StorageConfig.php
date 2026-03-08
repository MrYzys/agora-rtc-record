<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

/**
 * 第三方云存储的配置项
 */
class StorageConfig
{
    /**
     * 第三方云存储平台：
     * - 1：Amazon S3
     * - 2：阿里云
     * - 3：腾讯云
     * - 5：Microsoft Azure
     * - 6：谷歌云
     * - 7：华为云
     * - 8：百度智能云
     * - 11：其他 S3 协议云存储（需在 extensionParams.endpoint 中指定域名）
     */
    private int $vendor;

    /**
     * 第三方云存储指定的地区信息
     * 建议与发起请求的应用服务器在同一区域
     */
    private int $region;

    /**
     * 第三方云存储的 Bucket
     */
    private string $bucket;

    /**
     * 第三方云存储的 Access Key
     */
    private string $accessKey;

    /**
     * 第三方云存储的 Secret Key
     */
    private string $secretKey;

    /**
     * 第三方云存储的临时安全令牌（STS）
     * 目前仅支持 Amazon S3、阿里云、腾讯云
     */
    private ?string $stsToken = null;

    /**
     * stsToken 的过期时间戳（POSIX 时间，秒）
     * 建议使用 Uint64 存储，最短有效期不少于 4 小时
     */
    private ?int $stsExpiration = null;

    /**
     * 录制文件在第三方云存储中的存储位置前缀
     * 例如：["directory1","directory2"] 表示前缀为 "directory1/directory2/"
     * @var string[]|null
     */
    private ?array $fileNamePrefix = null;

    /**
     * 扩展参数，用于其他 S3 协议云存储等场景
     */
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
