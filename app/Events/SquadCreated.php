<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventProjector\ShouldBeStored;

class SquadCreated implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var int */
    public $userID;

    /** @var string */
    public $name;

    /** @var int */
    public $squadRankID;

    /** @var int */
    public $mobileStorageRankID;

    /** @var int */
    public $provinceID;

    /**
     * SquadCreated constructor.
     * @param int $userID
     * @param string $name
     * @param int $squadRankID
     * @param int $mobilStorageRankID
     * @param int $provinceID
     */
    public function __construct(int $userID, string $name, int $squadRankID, int $mobilStorageRankID, int $provinceID)
    {
        $this->userID = $userID;
        $this->name = $name;
        $this->squadRankID = $squadRankID;
        $this->mobileStorageRankID = $mobilStorageRankID;
        $this->provinceID = $provinceID;
    }

}
