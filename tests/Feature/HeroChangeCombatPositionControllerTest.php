<?php

namespace Tests\Feature;

use App\Domain\Actions\ChangeHeroCombatPositionAction;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HeroChangeCombatPositionControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Squad */
    protected $squad;

    /** @var CombatPosition */
    protected $combatPosition;

    /** @var Week */
    protected $week;

    /** @var ChangeHeroCombatPositionAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->hero = factory(Hero::class)->state('with-measurables')->create();
        $this->squad = $this->hero->squad;
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
    public function the_hero_must_be_owned_by_the_user()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        try {
            $this->json('POST', 'api/v1/heroes/' . $this->hero->slug . '/combat-position', [
                'position' => $this->combatPosition->id
            ]);
        } catch (AuthorizationException $exception) {

            $this->assertNotEquals($this->combatPosition->id, $this->hero->combat_position_id);
            return;
        }

        $this->fail("Exception not thrown");
    }
    /**
     * @test
     */
    public function it_will_return_a_hero_with_an_updated_combat_position()
    {
        $this->withoutExceptionHandling();

        Passport::actingAs($this->squad->user);
        $response = $this->json('POST', 'api/v1/heroes/' . $this->hero->slug . '/combat-position', [
            'position' => $this->combatPosition->id
        ]);

        $hero = $this->hero->fresh();
        $this->assertEquals($this->combatPosition->id, $hero->combat_position_id);
        $response->assertStatus(200)->assertJson([
            'data' => [
                'uuid' => $hero->uuid,
                'combatPositionID' => $hero->combat_position_id
            ]
        ]);
    }
}
