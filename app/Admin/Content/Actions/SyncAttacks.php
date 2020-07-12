<?php


namespace App\Admin\Content\Actions;


use App\Admin\Content\Sources\AttackSource;
use App\Domain\Models\Attack;
use App\Facades\Content;

class SyncAttacks
{
    public function execute()
    {
        $attackSources = Content::attacks();
        $attacks = Attack::all();
        $unSyncedSources = $attackSources->filter(function (AttackSource $attackSource) use ($attacks) {
            $match = $attacks->first(function (Attack $attack) use ($attackSource) {
                return $attackSource->getUuid() === (string) $attack->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $attackSource->isSynced($match);
        });

        $unSyncedSources->each(function (AttackSource $attackSource) {
            Attack::query()->updateOrCreate(
                [
                    'uuid' => $attackSource->getUuid(),
                ],
                [
                    'name' => $attackSource->getName(),
                    'attacker_position_id' => $attackSource->getAttackerPositionID(),
                    'target_position_id' => $attackSource->getTargetPositionID(),
                    'target_priority_id' => $attackSource->getTargetPriorityID(),
                    'damage_type_id' => $attackSource->getDamageTypeID(),
                    'tier' => $attackSource->getTier(),
                    'targets_count' => $attackSource->getTargetsCount()
                ]);
        });
    }
}
