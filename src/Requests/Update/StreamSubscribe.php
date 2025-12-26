<?php

declare(strict_types=1);

namespace MrYzys\AgoraRtcRecord\Requests\Update;

use stdClass;

class StreamSubscribe
{
    private ?UidList $audioUidList = null;

    private ?UidList $videoUidList = null;

    public function setAudioUidList(?UidList $audioUidList): self
    {
        $this->audioUidList = $audioUidList;
        return $this;
    }

    public function setVideoUidList(?UidList $videoUidList): self
    {
        $this->videoUidList = $videoUidList;
        return $this;
    }

    /**
     * @return array<string, mixed>|stdClass
     */
    public function toArray()
    {
        $payload = [];

        if ($this->audioUidList !== null) {
            $payload['audioUidList'] = $this->audioUidList->toArray();
        }

        if ($this->videoUidList !== null) {
            $payload['videoUidList'] = $this->videoUidList->toArray();
        }

        return $payload === [] ? new stdClass() : $payload;
    }
}
