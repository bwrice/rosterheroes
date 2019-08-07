<?php

use App\Domain\Actions\AddNewHeroToSquadAction;
use App\Domain\Actions\CreateSquadAction;
use App\Domain\Actions\CreateUserAction;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{
    /**
     * @param CreateUserAction $createUserAction
     * @param CreateSquadAction $createSquadAction
     * @param AddNewHeroToSquadAction $addNewHeroToSquadAction
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     */
    public function run(CreateUserAction $createUserAction, CreateSquadAction $createSquadAction, AddNewHeroToSquadAction $addNewHeroToSquadAction)
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

        $user = $createUserAction->execute('georigin@gmail.com', 'George Paul', 'password');
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
    }
}
