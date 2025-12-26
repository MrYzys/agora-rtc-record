<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Start;

use InvalidArgumentException;
use stdClass;

class ExtensionServiceConfig
{
    private ?string $errorHandlePolicy = null;

    /**
     * @var ExtensionService[]
     */
    private array $extensionServices = [];

    public function setErrorHandlePolicy(?string $errorHandlePolicy): self
    {
        $this->errorHandlePolicy = $errorHandlePolicy;
        return $this;
    }

    /**
     * @param ExtensionService[] $extensionServices
     */
    public function setExtensionServices(array $extensionServices): self
    {
        foreach ($extensionServices as $service) {
            if (!$service instanceof ExtensionService) {
                throw new InvalidArgumentException('extensionServices must contain ExtensionService instances');
            }
        }

        $this->extensionServices = array_values($extensionServices);
        return $this;
    }

    public function addExtensionService(ExtensionService $extensionService): self
    {
        $this->extensionServices[] = $extensionService;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toArray()
    {
        $payload = [];

        if ($this->errorHandlePolicy !== null) {
            $payload['errorHandlePolicy'] = $this->errorHandlePolicy;
        }

        if ($this->extensionServices !== []) {
            $payload['extensionServices'] = array_map(
                static fn (ExtensionService $service) => $service->toArray(),
                $this->extensionServices
            );
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
