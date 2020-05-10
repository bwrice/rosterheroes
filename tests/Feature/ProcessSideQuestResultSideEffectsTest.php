<?php

namespace Tests\Feature;

use App\Domain\ProcessSideQuestResultSideEffects;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class ProcessSideQuestResultSideEffectsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ProcessSideQuestResultSideEffects
     */
    protected function getDomainAction()
    {
        return app(ProcessSideQuestResultSideEffects::class);
    }

    /**
     * @return SideQuestResult
     */
    protected function getValidSideQuestResult(): SideQuestResult
    {
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->rewardsProcessed()->create();
        return $sideQuestResult;
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_side_effects_already_processed()
    {
        $processedAt = Date::now()->subDays(rand(1,10));
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->sideEffectsProcessed($processedAt)->create();
        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals($processedAt->timestamp, $sideQuestResult->fresh()->side_effects_processed_at->timestamp);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_combat_isnt_processed_at_yet()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $this->assertNull($sideQuestResult->combat_processed_at);
        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertNull($sideQuestResult->side_effects_processed_at);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_side_effects_processed_at()
    {
        $sideQuestResult = $this->getValidSideQuestResult();
        $this->getDomainAction()->execute($sideQuestResult);
        $this->assertNotNull($sideQuestResult->fresh()->side_effects_processed_at);
    }

    /**
     * @test
     */
    public function it_will_increase_heroes_damage_dealt_from_hero_damages_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $hero = HeroFactory::new()->forSquad($squad)->create();
        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroDamagesMinion($combatHero, null, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $beforeTotalDamageDealtForHero = $hero->damage_dealt;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeTotalDamageDealtForHero + $damageDealt, $hero->fresh()->damage_dealt);
    }

    /**
     * @test
     */
    public function it_will_increase_heroes_damage_dealt_from_hero_kills_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $hero = HeroFactory::new()->forSquad($squad)->create();
        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroKillsMinion($combatHero, null, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $beforeTotalDamageDealtForHero = $hero->damage_dealt;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeTotalDamageDealtForHero + $damageDealt, $hero->fresh()->damage_dealt);
    }

    /**
     * @test
     */
    public function it_will_increase_heroes_minion_kills_from_hero_kills_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $hero = HeroFactory::new()->forSquad($squad)->create();
        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();

        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroKillsMinion($combatHero)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $previousMinionKills = $hero->minion_kills;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($previousMinionKills + 1, $hero->fresh()->minion_kills);
    }

    /**
     * @test
     */
    public function it_will_increase_heroes_damage_taken_on_minion_damages_hero_side_quest_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $hero = HeroFactory::new()->forSquad($squad)->create();
        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->minionDamagesHero(null, null, $combatHero, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $previousDamageTaken = $hero->damage_taken;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($previousDamageTaken + $damageDealt, $hero->fresh()->damage_taken);
    }

    /**
     * @test
     */
    public function it_will_increase_heroes_damage_taken_on_minion_kills_hero_side_quest_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $hero = HeroFactory::new()->forSquad($squad)->create();
        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->minionKillsHero(null, null, $combatHero, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $previousDamageTaken = $hero->damage_taken;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($previousDamageTaken + $damageDealt, $hero->fresh()->damage_taken);
    }

    /**
     * @test
     */
    public function it_will_increase_squads_damage_dealt_from_hero_damages_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroDamagesMinion(null, null, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $beforeDamageDealtBySquad = $squad->damage_dealt;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeDamageDealtBySquad + $damageDealt, $squad->fresh()->damage_dealt);
    }

    /**
     * @test
     */
    public function it_will_increase_squads_damage_dealt_from_hero_kills_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroKillsMinion(null,null, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $beforeDamageDealtBySquad = $squad->damage_dealt;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeDamageDealtBySquad + $damageDealt, $squad->fresh()->damage_dealt);
    }

    /**
     * @test
     */
    public function it_will_increase_squads_minion_kills_from_hero_kills_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroKillsMinion()
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $previousMinionKills = $squad->minion_kills;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($previousMinionKills + 1, $squad->fresh()->minion_kills);
    }

    /**
     * @test
     */
    public function it_will_increase_items_damage_dealt_from_hero_damages_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $item = ItemFactory::new()->create();
        $heroCombatAttack = HeroCombatAttackFactory::new()->forItem($item->uuid)->create();
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroDamagesMinion(null, $heroCombatAttack, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $beforeDamageDealt = $item->damage_dealt;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeDamageDealt + $damageDealt, $item->fresh()->damage_dealt);
    }

    /**
     * @test
     */
    public function it_will_increase_squads_damage_taken_from_minion_damages_hero_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->minionDamagesHero(null,null, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $beforeDamageTake = $squad->damage_taken;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeDamageTake + $damageDealt, $squad->fresh()->damage_taken);
    }

    /**
     * @test
     */
    public function it_will_increase_squads_damage_taken_from_minion_kills_hero_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $sideQuestEvent = SideQuestEventFactory::new()
            ->minionKillsHero(null,null, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $beforeDamageTake = $squad->damage_taken;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeDamageTake + $damageDealt, $squad->fresh()->damage_taken);
    }

    /**
     * @test
     */
    public function it_will_increase_items_damage_dealt_from_hero_kills_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $item = ItemFactory::new()->create();
        $heroCombatAttack = HeroCombatAttackFactory::new()->forItem($item->uuid)->create();
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroKillsMinion(null, $heroCombatAttack, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $beforeDamageDealt = $item->damage_dealt;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($beforeDamageDealt + $damageDealt, $item->fresh()->damage_dealt);
    }

    /**
     * @test
     */
    public function it_will_increase_items_minion_kills_from_hero_kills_minion_events()
    {
        $sideQuestResult = $this->getValidSideQuestResult();

        $damageDealt = rand(50, 1000);
        $item = ItemFactory::new()->create();
        $heroCombatAttack = HeroCombatAttackFactory::new()->forItem($item->uuid)->create();
        $sideQuestEvent = SideQuestEventFactory::new()
            ->heroKillsMinion(null, $heroCombatAttack, null, $damageDealt)
            ->withSideQuestResultID($sideQuestResult->id)
            ->create();

        $previousMinionKills = $item->minion_kills;

        $this->getDomainAction()->execute($sideQuestResult);

        $this->assertEquals($previousMinionKills + 1, $item->fresh()->minion_kills);
    }
}
