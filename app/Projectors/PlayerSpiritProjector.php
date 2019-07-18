<?php

namespace App\Projectors;

use App\Domain\Models\PlayerSpirit;
use App\Events\PlayerSpiritCreationRequested;
use App\StorableEvents\PlayerSpiritCreated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class PlayerSpiritProjector implements Projector
{
    use ProjectsEvents;

    public function onPlayerSpiritCreated(PlayerSpiritCreated $event, string $aggregateUuid)
    {
        PlayerSpirit::query()->create([
            'uuid' => $aggregateUuid,
            'week_id' => $event->weekID,
            'player_id' => $event->playerID,
            'game_id' => $event->gameID,
            'player_game_log_id' => $event->playerGameLogID,
            'essence_cost' => $event->essenceCost,
            'energy' => $event->energy
        ]);
    }

}