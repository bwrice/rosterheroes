<?php


namespace App\Domain\Actions\Combat;


use App\Aggregates\SideQuestEventAggregate;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\Factories\Combat\CombatAttackFactory;
use App\SideQuestResult;
use Illuminate\Support\Str;

class CreateBattlefieldSetForSideQuestEvent
{
    public function execute(SideQuestResult $sideQuestResult, CombatSquad $combatSquad, SideQuestGroup $sideQuestGroup)
    {
        $uuid = (string) Str::uuid();
        $aggregate = SideQuestEventAggregate::retrieve($uuid);
        $aggregate->createBattlefieldSetEvent($sideQuestResult->id, [
            'combatSquad' => $combatSquad->toArray(),
            'sideQuestGroup' => $sideQuestGroup->toArray()
        ])->persist();
    }
}
