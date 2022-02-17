<?php

namespace Database\Seeders;

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Actions\CreateUserAction;
use App\Domain\Actions\GenerateItemFromBlueprintAction;
use App\Domain\Actions\RewardChestToSquad;
use App\Domain\Actions\StashItem;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * @param CreateUserAction $createUserAction
     * @param CreateSquadAction $createSquadAction
     * @param AddNewHeroToSquadAction $addNewHeroToSquadAction
     * @param GenerateItemFromBlueprintAction $generateItemFromBlueprintAction
     * @param RewardChestToSquad $rewardChestToSquad
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     */
    public function run(
        CreateUserAction $createUserAction,
        CreateSquadAction $createSquadAction,
        AddNewHeroToSquadAction $addNewHeroToSquadAction,
        GenerateItemFromBlueprintAction $generateItemFromBlueprintAction,
        RewardChestToSquad $rewardChestToSquad)
    {
        $user = $createUserAction->execute('bwrice83@gmail.com', 'Brian Rice', 'password');
        $user->email_verified_at = now();
        $user->save();
        $squad = $createSquadAction->execute($user->id, 'Middle Earthers');

        $heroes = [
            [
                'name' => 'Aragorn',
                'hero_race' => HeroRace::human(),
                'hero_class' => HeroClass::ranger()
            ],
            [
                'name' => 'Legolas',
                'hero_race' => HeroRace::elf(),
                'hero_class' => HeroClass::ranger()
            ],
            [
                'name' => 'Gimli',
                'hero_race' => HeroRace::dwarf(),
                'hero_class' => HeroClass::warrior()
            ],
            [
                'name' => 'Uruk Hai',
                'hero_race' => HeroRace::orc(),
                'hero_class' => HeroClass::sorcerer()
            ]
        ];

        foreach ($heroes as $hero) {
            $addNewHeroToSquadAction->execute($squad, $hero['name'], $hero['hero_class'], $hero['hero_race']);
        }

        /** @var \App\Domain\Models\ChestBlueprint $chestBlueprint */
        $chestBlueprint = \App\Domain\Models\ChestBlueprint::query()->first();
        foreach (range(1, 20) as $count) {
            $rewardChestToSquad->execute($chestBlueprint, $squad, null);
        }
    }
}
