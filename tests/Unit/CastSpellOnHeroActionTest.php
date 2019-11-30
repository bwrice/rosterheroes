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
        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        $this->squad = $this->hero->squad;
        $this->spell = Spell::query()->where('name', '=', 'Resolve')->first();
        $this->squad->spells()->save($this->spell);
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->adventuring_locks_at = Date::now()->addHour();
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
        $week->adventuring_locks_at = Date::now()->subHour();
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

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_spell_is_already_on_the_hero()
    {
        $this->hero->spells()->save($this->spell);
        try {
            $this->domainAction->execute($this->hero->fresh(), $this->spell->fresh());
        } catch (SpellCasterException $exception) {
            $this->assertEquals(SpellCasterException::CODE_SPELL_ALREADY_CASTED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");

    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_hero_does_not_have_enough_mana()
    {
        // Get a ton of spells that aren't the one we're trying to cast
        $spells = Spell::query()
            ->where('id', '!=', $this->spell->id)
            ->inRandomOrder()
            ->take(50)
            ->get();

        $this->hero->spells()->saveMany($spells);
        $this->assertLessThan($this->spell->manaCost(), $this->hero->getAvailableMana());

        try {
            $this->domainAction->execute($this->hero->fresh(), $this->spell);
        } catch (SpellCasterException $exception) {
            $this->assertEquals(SpellCasterException::CODE_NOT_ENOUGH_MANA, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_cast_a_spell()
    {
        $spell = $this->hero->spells()->where('id', '=', $this->spell->id)->first();
        $this->assertNull($spell);

        $this->domainAction->execute($this->hero, $this->spell);
        $this->hero = $this->hero->fresh();

        $spell = $this->hero->spells()->where('id', '=', $this->spell->id)->first();
        $this->assertNotNull($spell);
    }
}
