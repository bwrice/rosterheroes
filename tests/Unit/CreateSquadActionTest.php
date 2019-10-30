<?php

namespace Tests\Unit;

use App\Domain\Actions\CreateSquadAction;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSquadActionTest extends TestCase
{
    /** @var User */
    protected $user;

    /** @var CreateSquadAction */
    protected $domainAction;

    /** @var string */
    protected $squadName;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->domainAction = app(CreateSquadAction::class);
        $this->squadName = "Test Squad " . random_int(1, 9999999);
    }

    /**
     * @test
     */
    public function it_will_create_a_squad_for_a_user()
    {
        $this->domainAction->execute($this->user->id, $this->squadName);

        /** @var Squad $squad */
        $squad = Squad::query()->where('name', '=', $this->squadName)->first();
        $this->assertEquals($this->user->id, $squad->user_id);
    }

    /**
     * @test
     */
    public function the_squad_will_have_starting_properties()
    {
        $this->domainAction->execute($this->user->id, $this->squadName);

        /** @var Squad $squad */
        $squad = Squad::query()->where('name', '=', $this->squadName)->first();
        $this->assertEquals(Squad::STARTING_ESSENCE, $squad->spirit_essence, "Squad has starting spirit essence");
        $this->assertEquals(Squad::STARTING_GOLD, $squad->gold, "Squad has starting gold");
        $this->assertEquals(Squad::STARTING_FAVOR, $squad->favor, "Squad has starting favor");
        $this->assertEquals(Squad::STARTING_EXPERIENCE, $squad->experience, "Squad has starting experience");
    }

    /**
     * @test
     */
    public function it_will_have_the_correct_hero_posts()
    {
        $this->domainAction->execute($this->user->id, $this->squadName);

        /** @var Squad $squad */
        $squad = Squad::query()->where('name', '=', $this->squadName)->first();
        $this->assertEquals(count(Squad::STARTING_HERO_POSTS), $squad->heroPosts->count(), 'Squad has correct number of hero posts');

        foreach(Squad::STARTING_HERO_POST_TYPES as $heroPostTypeName => $count) {
            $heroPostTypeName = HeroPostType::query()->where('name', '=', $heroPostTypeName)->first();
            $this->assertEquals($count, $squad->heroPosts->where('hero_post_type_id', '=', $heroPostTypeName->id)->count(), "Correct amount of hero posts by hero post type");
        }
    }

    /**
     * @test
     */
    public function it_will_have_starting_spells()
    {
        $this->domainAction->execute($this->user->id, $this->squadName);

        /** @var Squad $squad */
        $squad = Squad::query()->where('name', '=', $this->squadName)->first();
        $squadSpells = $squad->spells()->whereIn('name', Squad::STARTING_SPELLS)->get();
        $this->assertEquals(count(Squad::STARTING_SPELLS), $squadSpells->count());
    }
}
