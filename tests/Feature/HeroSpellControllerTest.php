<?php

namespace Tests\Feature;

use App\Domain\Actions\CastSpellOnHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class HeroSpellControllerTest extends TestCase
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
        $this->squad = factory(Squad::class)->create();
        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        factory(HeroPost::class)->create([
            'squad_id' => $this->squad->id,
            'hero_id' => $this->hero->id
        ]);
        $this->spell = Spell::query()->where('name', '=', 'Resolve')->inRandomOrder()->first();
        $this->squad->spells()->save($this->spell);
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->everything_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);
    }

    /**
     * @test
     */
    public function a_user_cannot_cast_a_spell_on_a_hero_it_doesnt_own()
    {
        $response = $this->json('POST','/api/v1/heroes/' . $this->hero->slug . '/spells', [
            'spell' => $this->spell->id
        ]);

        $response->assertStatus(401);
    }
}
