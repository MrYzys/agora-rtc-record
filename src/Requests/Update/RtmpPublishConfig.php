<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

use InvalidArgumentException;
use stdClass;

class RtmpPublishConfig
{
    /**
     * @var RtmpPublishOutput[]
     */
    private array $outputs = [];

    /**
     * @param RtmpPublishOutput[] $outputs
     */
    public function setOutputs(array $outputs): self
    {
        foreach ($outputs as $output) {
            if (!$output instanceof RtmpPublishOutput) {
                throw new InvalidArgumentException('outputs must contain RtmpPublishOutput instances');
            }
        }

        $this->outputs = array_values($outputs);
        return $this;
    }

    public function addOutput(RtmpPublishOutput $output): self
    {
        $this->outputs[] = $output;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toArray()
    {
        if ($this->outputs === []) {
            return new stdClass();
        }

        return [
            'outputs' => array_map(
                static fn (RtmpPublishOutput $output) => $output->toArray(),
                $this->outputs
            ),
        ];
    }
}
