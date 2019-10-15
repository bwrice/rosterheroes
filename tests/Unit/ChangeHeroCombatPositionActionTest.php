<?php

namespace Tests\Unit;

use App\Domain\Actions\ChangeHeroCombatPositionAction;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\Week;
use App\Exceptions\ChangeCombatPositionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeHeroCombatPositionActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var CombatPosition */
    protected $combatPosition;

    /** @var Week */
    protected $week;

    /** @var ChangeHeroCombatPositionAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->hero = factory(Hero::class)->create();
        $this->combatPosition = CombatPosition::query()->where(function (Builder $builder) {
            $builder->where('id', '!=', $this->hero->combat_position_id);
        })->inRandomOrder()->first();

        $this->week = factory(Week::class)->create([
            'everything_locks_at' => Date::now()->addHour()
        ]);

        Week::setTestCurrent($this->week);

        $this->domainAction = app(ChangeHeroCombatPositionAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_week_is_locked()
    {
        $this->week->everything_locks_at = Date::now()->subHour();

        try {
            $this->domainAction->execute($this->hero, $this->combatPosition);
        } catch (ChangeCombatPositionException $exception) {

            $this->assertNotEquals($this->hero->combat_position_id, $this->combatPosition->id);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_a_hero_combat_position()
    {
        $this->domainAction->execute($this->hero, $this->combatPosition);

        $this->hero = $this->hero->fresh();
        $this->assertEquals($this->hero->combat_position_id, $this->combatPosition->id);
    }
}
