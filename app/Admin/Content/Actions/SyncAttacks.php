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
        $attackUuids = Attack::query()->pluck('uuid')->toArray();
        $missingSources = $attackSources->filter(function (AttackSource $attackSource) use ($attackUuids) {
            return ! in_array($attackSource->getUuid(), $attackUuids);
        });

        $missingSources->each(function (AttackSource $attackSource) {
            Attack::query()->create([
                'uuid' => $attackSource->getUuid(),
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
