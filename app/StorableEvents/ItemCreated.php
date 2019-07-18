<?php

namespace App\StorableEvents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventProjector\ShouldBeStored;

final class ItemCreated implements ShouldBeStored
{

    /**
     * @var int
     */
    public $itemClassID;
    /**
     * @var int
     */
    public $itemTypeID;
    /**
     * @var int
     */
    public $materialTypeID;
    /**
     * @var int
     */
    public $itemBlueprintID;
    /**
     * @var string
     */
    public $name;

    public function __construct(int $itemClassID, int $itemTypeID, int $materialTypeID, int $itemBlueprintID, string $name = null)
    {

        $this->itemClassID = $itemClassID;
        $this->itemTypeID = $itemTypeID;
        $this->materialTypeID = $materialTypeID;
        $this->itemBlueprintID = $itemBlueprintID;
        $this->name = $name;
    }
}
