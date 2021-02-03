<?php

namespace Tests\Feature;

use App\Domain\Models\HeroPostType;
use App\Facades\HeroPostTypeFacade;
use App\Factories\Models\HeroPostFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HeroPostTypeServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function cheapest_for_squad_will_return_all_post_types_for_starting_squad()
    {
        $squad = SquadFactory::new()->withStartingHeroes()->create();

        $heroPostTypes = HeroPostTypeFacade::cheapestForSquad($squad);
        $expected = HeroPostType::query()->whereIn('name', [
            HeroPostType::HUMAN,
            HeroPostType::ELF,
            HeroPostType::DWARF,
            HeroPostType::ORC,
        ])->pluck('id')->toArray();
        $this->assertArrayElementsEqual($expected, $heroPostTypes->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function cheapest_for_squad_will_exclude_more_post_types_with_multiple_heroes()
    {
        $squad = SquadFactory::new()->withStartingHeroes()->create();

        /** @var HeroPostType $heroPostType */
        $heroPostType = HeroPostType::query()
            ->where('name', '=', HeroPostType::HUMAN)
            ->first();

        HeroPostFactory::new()
            ->forSquad($squad->id)
            ->forHeroPostType($heroPostType->id)
            ->create();

        $heroPostTypes = HeroPostTypeFacade::cheapestForSquad($squad);

        // Human should not be there because we created a 2nd hero-post of type Human
        $expected = HeroPostType::query()->whereIn('name', [
            HeroPostType::ELF,
            HeroPostType::DWARF,
            HeroPostType::ORC,
        ])->pluck('id')->toArray();

        $this->assertArrayElementsEqual($expected, $heroPostTypes->pluck('id')->toArray());
    }
}
