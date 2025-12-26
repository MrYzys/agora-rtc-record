<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord;

use stdClass;
use MrYzys\AgoraRtcRecord\Exceptions\CloudRecordingException;
use MrYzys\AgoraRtcRecord\Requests\Acquire\AcquireRequest;
use MrYzys\AgoraRtcRecord\Requests\Query\QueryRequest;
use MrYzys\AgoraRtcRecord\Requests\Start\StartRequest;
use MrYzys\AgoraRtcRecord\Requests\Stop\StopRequest;
use MrYzys\AgoraRtcRecord\Requests\Update\UpdateRequest;
use MrYzys\AgoraRtcRecord\Requests\UpdateLayout\UpdateLayoutRequest;

/**
 * Minimal wrapper around Shengwang/Agora cloud recording REST APIs.
 */
class CloudRecordingClient
{
    private string $appId;

    private string $customerId;

    private string $customerCertificate;

    private string $baseUrl;

    private int $timeout;

    public function __construct(
        string $appId,
        string $customerId,
        string $customerCertificate,
        string $baseUrl = 'https://api.sd-rtn.com',
        int $timeout = 10
    ) {
        $this->appId = $appId;
        $this->customerId = $customerId;
        $this->customerCertificate = $customerCertificate;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }

    /**
     * Acquire a resource ID that is required before starting a recording session.
     *
     * @return array<string, mixed>
     */
    public function acquire(AcquireRequest $request): array
    {
        $path = sprintf('/v1/apps/%s/cloud_recording/acquire', $this->appId);

        return $this->sendRequest('POST', $path, $request->toArray());
    }

    /**
     * Start the recording with the provided resource id and the chosen mode (mix/individual/web).
     *
     * @return array<string, mixed>
     */
    public function start(StartRequest $request): array
    {
        $path = sprintf(
            '/v1/apps/%s/cloud_recording/resourceid/%s/mode/%s/start',
            $this->appId,
            urlencode($request->getResourceId()),
            urlencode($request->getMode())
        );

        return $this->sendRequest('POST', $path, $request->toArray());
    }

    /**
     * Query the status of an ongoing recording session.
     *
     * @return array<string, mixed>
     */
    public function query(QueryRequest $request): array
    {
        $path = sprintf(
            '/v1/apps/%s/cloud_recording/resourceid/%s/sid/%s/mode/%s/query',
            $this->appId,
            urlencode($request->getResourceId()),
            urlencode($request->getSid()),
            urlencode($request->getMode())
        );

        return $this->sendRequest('GET', $path);
    }

    /**
     * Stop an ongoing recording session.
     *
     * @return array<string, mixed>
     */
    public function stop(StopRequest $request): array
    {
        $path = sprintf(
            '/v1/apps/%s/cloud_recording/resourceid/%s/sid/%s/mode/%s/stop',
            $this->appId,
            urlencode($request->getResourceId()),
            urlencode($request->getSid()),
            urlencode($request->getMode())
        );

        return $this->sendRequest('POST', $path, $request->toArray());
    }

    /**
     * Update recording settings (subscription lists, web recording options, etc.).
     *
     * @return array<string, mixed>
     */
    public function update(UpdateRequest $request): array
    {
        $path = sprintf(
            '/v1/apps/%s/cloud_recording/resourceid/%s/sid/%s/mode/%s/update',
            $this->appId,
            urlencode($request->getResourceId()),
            urlencode($request->getSid()),
            urlencode($request->getMode())
        );

        return $this->sendRequest('POST', $path, $request->toArray());
    }

    /**
     * Update the mix layout configuration.
     *
     * @return array<string, mixed>
     */
    public function updateLayout(UpdateLayoutRequest $request): array
    {
        $path = sprintf(
            '/v1/apps/%s/cloud_recording/resourceid/%s/sid/%s/mode/%s/updateLayout',
            $this->appId,
            urlencode($request->getResourceId()),
            urlencode($request->getSid()),
            urlencode($request->getMode())
        );

        return $this->sendRequest('POST', $path, $request->toArray());
    }

    /**
     * Execute the HTTP call with curl and map errors to CloudRecordingException.
     *
     * @param array<string, mixed>|stdClass|null $payload
     * @return array<string, mixed>
     */
    private function sendRequest(string $method, string $path, $payload = null): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');

        $ch = curl_init($url);
        if ($ch === false) {
            throw new CloudRecordingException('Unable to initialise cURL handle');
        }

        $headers = [
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->customerId . ':' . $this->customerCertificate),
        ];

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
        ];

        if ($payload !== null) {
            $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
            if ($body === false) {
                throw new CloudRecordingException('Failed to encode request payload: ' . json_last_error_msg());
            }

            $options[CURLOPT_POSTFIELDS] = $body;
            $headers[] = 'Content-Type: application/json;charset=utf-8';
        }

        $options[CURLOPT_HTTPHEADER] = $headers;

        curl_setopt_array($ch, $options);

        $responseBody = curl_exec($ch);
        if ($responseBody === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new CloudRecordingException('HTTP request failed: ' . $error);
        }

        $statusCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE) ?: 0;
        curl_close($ch);

        $decoded = json_decode($responseBody, true);
        if ($decoded === null && $responseBody !== '' && json_last_error() !== JSON_ERROR_NONE) {
            throw new CloudRecordingException('Failed to decode API response: ' . json_last_error_msg(), $statusCode);
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            $message = isset($decoded['message']) ? (string) $decoded['message'] : 'Agora cloud recording API error.';
            throw new CloudRecordingException($message, $statusCode, $decoded ?? null);
        }

        return $decoded ?? [];
    }
}
