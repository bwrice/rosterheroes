<?php

namespace App\Listeners;

use App\Events\SquadCreated;
use App\Events\WagonCreated;
use App\Squad;
use App\Wagons\Wagon;
use App\Wagons\WagonSizes\WagonSize;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateNewWagon
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SquadCreated $event
     * @return void
     */
    public function handle(SquadCreated $event)
    {
        $squad = $event->squad;
        /** @var Wagon $wagon */
        $wagon = $squad->wagon()->create([
            'squad_id' => $squad->id,
            'wagon_size_id' => WagonSize::getStarting()->id
        ]);

        event(new WagonCreated($wagon));
    }
}
