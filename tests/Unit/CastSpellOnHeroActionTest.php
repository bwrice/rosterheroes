<?php

namespace Tests\Unit;

use App\Domain\Actions\CastSpellOnHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\SpellCasterException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CastSpellOnHeroActionTest extends TestCase
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
        $this->spell = Spell::query()->inRandomOrder()->first();
        $this->squad->spells()->save($this->spell);
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->everything_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);
        $this->domainAction = app(CastSpellOnHeroAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_week_is_locked()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->everything_locks_at = Date::now()->subHour();
        $week->save();

        Week::setTestCurrent($week);

        try {
            $this->domainAction->execute($this->hero, $this->spell);
        } catch (SpellCasterException $exception) {
            $this->assertEquals(SpellCasterException::CODE_WEEK_LOCKED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_spell_is_not_in_the_squads_library()
    {
        $this->squad->spells()->sync([]); // clear spells
        try {
            $this->domainAction->execute($this->hero, $this->spell);
        } catch (SpellCasterException $exception) {
            $this->assertEquals(SpellCasterException::CODE_SPELL_NOT_OWNED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
