<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Acquire;

/**
 * Value object describing the payload of the acquire call.
 */
class AcquireRequest
{
    /**
     * 频道名。
     * - 单流录制和合流录制：设置待录制的频道名
     * - 页面录制：用于区分录制进程，字符串长度不得超过 128 字节
     * 注意：通过 appid、cname 和 uid 可以定位一个唯一的录制实例
     */
    private string $channelName;

    /**
     * 云端录制服务在频道内使用的 UID，用于标识频道内的录制服务。
     * 取值范围 1 到 (2^32-1)，不可设置为 0，不能与当前频道内的任何 UID 重复
     */
    private string $uid;

    private AcquireClientRequest $clientRequest;

    public function __construct(string $channelName, string $uid, AcquireClientRequest $clientRequest)
    {
        $this->channelName = $channelName;
        $this->uid = $uid;
        $this->clientRequest = $clientRequest;
    }

    /**
     * Export the request into the Agora REST payload shape.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'cname' => $this->channelName,
            'uid' => $this->uid,
            'clientRequest' => $this->clientRequest->toPayload(),
        ];
    }
}
