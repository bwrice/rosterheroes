<?php

namespace Tests\Unit;

use App\Domain\Actions\RemoveSpellFromHeroAction;
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

class RemoveSpellFromHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var Spell */
    protected $spell;

    /** @var RemoveSpellFromHeroAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->create();
        $this->squad = $this->hero->squad;
        $this->spell = Spell::query()->where('name', '=', 'Resolve')->inRandomOrder()->first();
        $this->squad->spells()->save($this->spell);
        $this->hero->spells()->save($this->spell);
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->adventuring_locks_at = Date::now()->addHour();
        $week->save();
        Week::setTestCurrent($week);
        $this->domainAction = app(RemoveSpellFromHeroAction::class);
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
    public function it_will_throw_an_exception_if_the_spell_is_not_already_on_the_hero()
    {
        $this->hero->spells()->sync([]); // clear all hero spells
        try {
            $this->domainAction->execute($this->hero->fresh(), $this->spell);
        } catch (SpellCasterException $exception) {
            $this->assertEquals(SpellCasterException::CODE_SPELL_NO_EXISTING_SPELL, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_remove_a_spell_from_a_hero()
    {
        $spell = $this->hero->spells()->find($this->spell->id);
        $this->assertNotNull($spell);
        $hero = $this->domainAction->execute($this->hero, $this->spell);
        $this->hero = $hero;
        $spell = $this->hero->spells()->find($this->spell->id);
        $this->assertNull($spell);
    }
}
