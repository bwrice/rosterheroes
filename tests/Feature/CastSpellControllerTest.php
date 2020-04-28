<?php

namespace Tests\Feature;

use App\Domain\Actions\CastSpellOnHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CastSpellControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var Spell */
    protected $spell;

    /** @var CastSpellOnHeroAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        $this->squad = $this->hero->squad;
        $this->spell = Spell::query()->where('name', '=', 'Resolve')->inRandomOrder()->first();
        $this->squad->spells()->save($this->spell);
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->adventuring_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);
    }

    /**
     * @test
     */
    public function a_user_cannot_cast_a_spell_on_a_hero_it_doesnt_own()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);

        $response = $this->json('POST','/api/v1/heroes/' . $this->hero->slug . '/cast-spell', [
            'spell' => $this->spell->id
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_correct_hero_response_after_casting_spell()
    {
        Sanctum::actingAs($this->squad->user);

        $response = $this->json('POST','/api/v1/heroes/' . $this->hero->slug . '/cast-spell', [
            'spell' => $this->spell->id
        ]);

        $response->assertJson([
            'data' => [
                'uuid' => $this->hero->uuid,
                'spells' => [
                    [
                        'id' => $this->spell->id
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_will_return_an_error_response_if_a_spell_caster_exception_is_thrown()
    {
        // Get a ton of spells that aren't the one we're trying to cast
        $spells = Spell::query()
            ->where('id', '!=', $this->spell->id)
            ->inRandomOrder()
            ->take(50)
            ->get();

        $this->hero->spells()->saveMany($spells);
        $this->assertLessThan($this->spell->manaCost(), $this->hero->getAvailableMana());

        Sanctum::actingAs($this->squad->user);

        $response = $this->json('POST','/api/v1/heroes/' . $this->hero->slug . '/cast-spell', [
            'spell' => $this->spell->id
        ]);

        $response->assertStatus(422)->assertJson([
            'errors' => [
                'spellCaster' => []
            ]
        ]);
    }
}
