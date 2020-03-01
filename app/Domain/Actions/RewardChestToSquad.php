<?php


namespace App\Domain\Actions;


use App\Aggregates\ChestAggregate;
use App\Aggregates\SquadAggregate;
use App\Chest;
use App\ChestBlueprint;
use App\Domain\Models\Squad;
use Illuminate\Support\Str;

class RewardChestToSquad
{
    public function execute(ChestBlueprint $chestBlueprint, Squad $squad)
    {
        $uuid = (string) Str::uuid();
        $chestAggregate = ChestAggregate::retrieve($uuid);
        $chestGold = rand($chestBlueprint->min_gold, $chestBlueprint->max_gold);
        $chestAggregate->createNewChest($chestBlueprint->grade, $chestGold, $squad->id, $chestBlueprint->id);
        $chestAggregate->persist();

        return Chest::findUuidOrFail($uuid);
    }
}
