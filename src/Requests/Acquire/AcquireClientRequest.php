<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

use stdClass;

/**
 * Container for the nested clientRequest block of the acquire call.
 */
class AcquireClientRequest
{
    /**
     * 云端录制资源使用场景：
     * - 0：单流录制、合流录制
     * - 1：页面录制
     */
    private ?int $scene = null;

    /**
     * 云端录制 RESTful API 的调用时效（小时）。
     * 从成功开启云端录制并获得 sid 后开始计算
     */
    private ?int $resourceExpiredHour = null;

    /**
     * 另一路或几路录制任务的 resourceId，用于排除指定的录制资源
     * @var string[]|null
     */
    private ?array $excludeResourceIds = null;

    /**
     * 指定使用某个区域的资源进行录制：
     * - 0：根据发起请求的区域就近调用资源
     * - 1：中国
     * - 2：东南亚
     * - 3：欧洲
     * - 4：北美
     */
    private ?int $regionAffinity = null;

    /**
     * 预配置 start 参数，用于提升可用性并优化负载均衡
     * 注意：如填写则必须与后续 start 请求中的 clientRequest 完全一致
     */
    private ?StartParameter $startParameter = null;

    private ?RecordingConfig $recordingConfig = null;

    public function setScene(?int $scene): self
    {
        $this->scene = $scene;
        return $this;
    }

    public function setResourceExpiredHour(?int $hours): self
    {
        $this->resourceExpiredHour = $hours;
        return $this;
    }

    /**
     * @param string[]|null $resourceIds
     */
    public function setExcludeResourceIds(?array $resourceIds): self
    {
        $this->excludeResourceIds = $resourceIds;
        return $this;
    }

    public function setRegionAffinity(?int $regionAffinity): self
    {
        $this->regionAffinity = $regionAffinity;
        return $this;
    }

    public function setStartParameter(?StartParameter $startParameter): self
    {
        $this->startParameter = $startParameter;
        return $this;
    }

    public function setRecordingConfig(?RecordingConfig $recordingConfig): self
    {
        $this->recordingConfig = $recordingConfig;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toPayload()
    {
        $payload = [];

        if ($this->scene !== null) {
            $payload['scene'] = $this->scene;
        }

        if ($this->resourceExpiredHour !== null) {
            $payload['resourceExpiredHour'] = $this->resourceExpiredHour;
        }

        if ($this->excludeResourceIds !== null) {
            $payload['excludeResourceIds'] = $this->excludeResourceIds;
        }

        if ($this->regionAffinity !== null) {
            $payload['regionAffinity'] = $this->regionAffinity;
        }

        if ($this->startParameter !== null) {
            $payload['startParameter'] = $this->startParameter->toPayload();
        }

        if ($this->recordingConfig !== null) {
            $payload['recordingConfig'] = $this->recordingConfig->toPayload();
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
