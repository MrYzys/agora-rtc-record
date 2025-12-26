<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

use stdClass;

class ExtensionService
{
    private string $serviceName;

    private ?string $errorHandlePolicy = null;

    /**
     * @var ServiceParamInterface|array<string, mixed>
     */
    private $serviceParam;

    /**
     * @param ServiceParamInterface|array<string, mixed> $serviceParam
     */
    public function __construct(string $serviceName, $serviceParam)
    {
        $this->serviceName = $serviceName;
        $this->serviceParam = $serviceParam;
    }

    public function setErrorHandlePolicy(?string $errorHandlePolicy): self
    {
        $this->errorHandlePolicy = $errorHandlePolicy;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $param = $this->serviceParam instanceof ServiceParamInterface
            ? $this->serviceParam->toArray()
            : $this->serviceParam;

        if ($param === []) {
            $param = new stdClass();
        }

        $payload = [
            'serviceName' => $this->serviceName,
            'serviceParam' => $param,
        ];

        if ($this->errorHandlePolicy !== null) {
            $payload['errorHandlePolicy'] = $this->errorHandlePolicy;
        }

        return $payload;
    }
}
