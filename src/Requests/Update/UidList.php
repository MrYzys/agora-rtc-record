<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

use InvalidArgumentException;
use stdClass;

class UidList
{
    private string $type;

    /**
     * @var string[]|null
     */
    private ?array $subscribeUids = null;

    /**
     * @var string[]|null
     */
    private ?array $unsubscribeUids = null;

    public function __construct(string $type)
    {
        if (!in_array($type, ['audio', 'video'], true)) {
            throw new InvalidArgumentException('UidList type must be audio or video');
        }

        $this->type = $type;
    }

    /**
     * @param string[]|null $uids
     */
    public function setSubscribeUids(?array $uids): self
    {
        if ($uids !== null) {
            $this->assertStringArray($uids, 'subscribe' . ucfirst($this->type) . 'Uids');
        }

        $this->subscribeUids = $uids;
        return $this;
    }

    /**
     * @param string[]|null $uids
     */
    public function setUnsubscribeUids(?array $uids): self
    {
        if ($uids !== null) {
            $this->assertStringArray($uids, 'unSubscribe' . ucfirst($this->type) . 'Uids');
        }

        $this->unsubscribeUids = $uids;
        return $this;
    }

    /**
     * @return array<string, array<int, string>>|stdClass
     */
    public function toArray()
    {
        $payload = [];

        if ($this->subscribeUids !== null) {
            $payload['subscribe' . ucfirst($this->type) . 'Uids'] = $this->subscribeUids;
        }

        if ($this->unsubscribeUids !== null) {
            $payload['unSubscribe' . ucfirst($this->type) . 'Uids'] = $this->unsubscribeUids;
        }

        return $payload === [] ? new stdClass() : $payload;
    }

    private function assertStringArray(array $values, string $field): void
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new InvalidArgumentException($field . ' must be an array of strings');
            }
        }
    }
}
