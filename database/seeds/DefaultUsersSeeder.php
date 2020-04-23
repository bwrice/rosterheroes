<?php

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Actions\CreateUserAction;
use App\Domain\Actions\GenerateItemFromBlueprintAction;
use App\Domain\Actions\RewardChestToSquad;
use App\Domain\Actions\StashItemFromMobileStorage;
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
        $squad = $createSquadAction->execute($user->id, 'My Squad');

        $heroes = [
            [
                'name' => 'My Human',
                'hero_race' => HeroRace::human(),
                'hero_class' => HeroClass::ranger()
            ],
            [
                'name' => 'My Elf',
                'hero_race' => HeroRace::elf(),
                'hero_class' => HeroClass::sorcerer()
            ],
            [
                'name' => 'My Dwarf',
                'hero_race' => HeroRace::dwarf(),
                'hero_class' => HeroClass::warrior()
            ],
            [
                'name' => 'My Orc',
                'hero_race' => HeroRace::orc(),
                'hero_class' => HeroClass::warrior()
            ]
        ];

        foreach ($heroes as $hero) {
            $addNewHeroToSquadAction->execute($squad, $hero['name'], $hero['hero_class'], $hero['hero_race']);
        }

        /** @var \App\Domain\Models\ItemBlueprint $blueprint */
        $blueprint = \App\Domain\Models\ItemBlueprint::query()->where('reference_id', '=', \App\Domain\Models\ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM)->first();

        $this->seedRandomItemsForSquad($generateItemFromBlueprintAction, $squad, $blueprint);
        $this->rewardChestsToSquad($rewardChestToSquad, $squad);


        $user = $createUserAction->execute('georigin@gmail.com', 'George Paul Puthukkeril', 'password');
        $squad = $createSquadAction->execute($user->id, 'Squad of George');

        $heroes = [
            [
                'name' => 'Human Ranger',
                'hero_race' => HeroRace::human(),
                'hero_class' => HeroClass::ranger()
            ],
            [
                'name' => 'Elf Sorcerer',
                'hero_race' => HeroRace::elf(),
                'hero_class' => HeroClass::sorcerer()
            ],
            [
                'name' => 'Dwarf Warrior',
                'hero_race' => HeroRace::dwarf(),
                'hero_class' => HeroClass::warrior()
            ],
            [
                'name' => 'Orc Warrior',
                'hero_race' => HeroRace::orc(),
                'hero_class' => HeroClass::warrior()
            ]
        ];

        foreach ($heroes as $hero) {
            $addNewHeroToSquadAction->execute($squad, $hero['name'], $hero['hero_class'], $hero['hero_race']);
        }

        $this->seedRandomItemsForSquad($generateItemFromBlueprintAction, $squad, $blueprint);

        $this->rewardChestsToSquad($rewardChestToSquad, $squad);
    }

    /**
     * @param GenerateItemFromBlueprintAction $generateItemFromBlueprintAction
     * @param \App\Domain\Models\Squad $squad
     * @param \App\Domain\Models\ItemBlueprint $blueprint
     */
    protected function seedRandomItemsForSquad(GenerateItemFromBlueprintAction $generateItemFromBlueprintAction, \App\Domain\Models\Squad $squad, \App\Domain\Models\ItemBlueprint $blueprint)
    {
        $localStash = $squad->getLocalStash();
        foreach (range(1, 20) as $count) {
            $item = $generateItemFromBlueprintAction->execute($blueprint);
            if ($count % 4 === 0) {
                $item->attachToHasItems($localStash);
            } else {
                $item->attachToHasItems($squad);
            }
        }
    }

    /**
     * @param RewardChestToSquad $rewardChestToSquad
     * @param \App\Domain\Models\Squad $squad
     */
    protected function rewardChestsToSquad(RewardChestToSquad $rewardChestToSquad, \App\Domain\Models\Squad $squad)
    {
        /** @var \App\ChestBlueprint $chestBlueprint */
        $chestBlueprint = \App\ChestBlueprint::query()
            ->where('reference_id', '=', \App\ChestBlueprint::FULLY_RANDOM_MEDIUM)
            ->first();
        foreach (range(1, 5) as $count) {
            $rewardChestToSquad->execute($chestBlueprint, $squad, null);
        }
    }
}
