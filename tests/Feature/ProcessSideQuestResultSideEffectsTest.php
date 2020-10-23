<?php

namespace Tests\Feature;

use App\Domain\ProcessSideQuestResultSideEffects;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Domain\Models\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

/**
 * Class ProcessSideQuestResultSideEffectsTest
 * @package Tests\Feature
 *
 * @group ignore
 */
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

//    /**
//     * @test
//     */
//    public function it_will_throw_an_exception_if_side_effects_already_processed()
//    {
//        $processedAt = Date::now()->subDays(rand(1,10));
//        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->sideEffectsProcessed($processedAt)->create();
//        try {
//            $this->getDomainAction()->execute($sideQuestResult);
//        } catch (\Exception $exception) {
//            $this->assertEquals($processedAt->timestamp, $sideQuestResult->fresh()->side_effects_processed_at->timestamp);
//            return;
//        }
//        $this->fail("Exception not thrown");
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_throw_an_exception_if_the_combat_isnt_processed_at_yet()
//    {
//        $sideQuestResult = SideQuestResultFactory::new()->create();
//        $this->assertNull($sideQuestResult->combat_processed_at);
//        try {
//            $this->getDomainAction()->execute($sideQuestResult);
//        } catch (\Exception $exception) {
//            $this->assertNull($sideQuestResult->side_effects_processed_at);
//            return;
//        }
//        $this->fail("Exception not thrown");
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_update_side_effects_processed_at()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $this->getDomainAction()->execute($sideQuestResult);
//        $this->assertNotNull($sideQuestResult->fresh()->side_effects_processed_at);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_heroes_damage_dealt_from_hero_damages_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroDamagesMinion($combatHero, null, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $beforeTotalDamage = $hero->damage_dealt;
//        $beforeMinionDamage = $hero->minion_damage_dealt;
//        $beforeSideQuestDamage = $hero->side_quest_damage_dealt;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($beforeTotalDamage + $damageDealt, $hero->damage_dealt);
//        $this->assertEquals($beforeMinionDamage + $damageDealt, $hero->minion_damage_dealt);
//        $this->assertEquals($beforeSideQuestDamage + $damageDealt, $hero->side_quest_damage_dealt);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_heroes_damage_dealt_from_hero_kills_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroKillsMinion($combatHero, null, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $beforeTotalDamage = $hero->damage_dealt;
//        $beforeMinionDamage = $hero->minion_damage_dealt;
//        $beforeSideQuestDamage = $hero->side_quest_damage_dealt;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($beforeTotalDamage + $damageDealt, $hero->damage_dealt);
//        $this->assertEquals($beforeMinionDamage + $damageDealt, $hero->minion_damage_dealt);
//        $this->assertEquals($beforeSideQuestDamage + $damageDealt, $hero->side_quest_damage_dealt);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_heroes_minion_kills_from_hero_kills_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroKillsMinion($combatHero)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $previousKills = $hero->combat_kills;
//        $previousMinionKills = $hero->minion_kills;
//        $previousSideQuestKills = $hero->side_quest_kills;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($previousKills + 1, $hero->combat_kills);
//        $this->assertEquals($previousMinionKills + 1, $hero->minion_kills);
//        $this->assertEquals($previousSideQuestKills + 1, $hero->side_quest_kills);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_heroes_damage_taken_on_minion_damages_hero_side_quest_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->minionDamagesHero(null, null, $combatHero, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $previousDamageTaken = $hero->damage_taken;
//        $previousMinionDamageTaken = $hero->minion_damage_taken;
//        $previousSideQuestDamageTaken = $hero->side_quest_damage_taken;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($previousDamageTaken + $damageDealt, $hero->damage_taken);
//        $this->assertEquals($previousMinionDamageTaken + $damageDealt, $hero->minion_damage_taken);
//        $this->assertEquals($previousSideQuestDamageTaken + $damageDealt, $hero->side_quest_damage_taken);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_heroes_damage_taken_on_minion_kills_hero_side_quest_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->minionKillsHero(null, null, $combatHero, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $previousDamageTaken = $hero->damage_taken;
//        $previousMinionDamageTaken = $hero->minion_damage_taken;
//        $previousSideQuestDamageTaken = $hero->side_quest_damage_taken;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($previousDamageTaken + $damageDealt, $hero->damage_taken);
//        $this->assertEquals($previousMinionDamageTaken + $damageDealt, $hero->minion_damage_taken);
//        $this->assertEquals($previousSideQuestDamageTaken + $damageDealt, $hero->side_quest_damage_taken);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_hero_death_counts_on_minion_kills_hero_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->minionKillsHero(null, null, $combatHero)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $beforeSideQuestDeaths = $hero->side_quest_deaths;
//        $beforeMinionDeaths = $hero->minion_deaths;
//        $beforeCombatDeaths = $hero->combat_deaths;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($beforeSideQuestDeaths + 1, $hero->side_quest_deaths);
//        $this->assertEquals($beforeMinionDeaths + 1, $hero->minion_deaths);
//        $this->assertEquals($beforeCombatDeaths + 1, $hero->combat_deaths);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_blocks_for_a_hero_for_hero_blocks_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//        $hero = HeroFactory::new()->forSquad($squad)->create();
//        $combatHero = CombatHeroFactory::new()->forHero($hero->uuid)->create();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroBlocksMinion($combatHero)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $beforeBlocks = $hero->attacks_blocked;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $hero = $hero->fresh();
//
//        $this->assertEquals($beforeBlocks + 1, $hero->attacks_blocked);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_squads_damage_dealt_from_hero_damages_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroDamagesMinion(null, null, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $beforeTotalDamage = $squad->damage_dealt;
//        $beforeMinionDamageDealt = $squad->minion_damage_dealt;
//        $beforeSideQuestDamageDealt = $squad->side_quest_damage_dealt;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $squad = $squad->fresh();
//
//        $this->assertEquals($beforeTotalDamage + $damageDealt, $squad->damage_dealt);
//        $this->assertEquals($beforeMinionDamageDealt + $damageDealt, $squad->minion_damage_dealt);
//        $this->assertEquals($beforeSideQuestDamageDealt + $damageDealt, $squad->side_quest_damage_dealt);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_squads_damage_dealt_from_hero_kills_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroKillsMinion(null,null, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $beforeTotalDamage = $squad->damage_dealt;
//        $beforeMinionDamageDealt = $squad->minion_damage_dealt;
//        $beforeSideQuestDamageDealt = $squad->side_quest_damage_dealt;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $squad = $squad->fresh();
//
//        $this->assertEquals($beforeTotalDamage + $damageDealt, $squad->damage_dealt);
//        $this->assertEquals($beforeMinionDamageDealt + $damageDealt, $squad->minion_damage_dealt);
//        $this->assertEquals($beforeSideQuestDamageDealt + $damageDealt, $squad->side_quest_damage_dealt);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_squads_minion_kills_from_hero_kills_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroKillsMinion()
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $previousKills = $squad->combat_kills;
//        $previousMinionKills = $squad->minion_kills;
//        $previousSideQuestKills = $squad->side_quest_kills;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $squad = $squad->fresh();
//
//        $this->assertEquals($previousKills + 1, $squad->combat_kills);
//        $this->assertEquals($previousMinionKills + 1, $squad->minion_kills);
//        $this->assertEquals($previousSideQuestKills + 1, $squad->side_quest_kills);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_squads_damage_taken_from_minion_damages_hero_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->minionDamagesHero(null,null, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $previousDamageTaken = $squad->damage_taken;
//        $previousMinionDamageTaken = $squad->minion_damage_taken;
//        $previousSideQuestDamageTaken = $squad->side_quest_damage_taken;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $squad = $squad->fresh();
//
//        $this->assertEquals($previousDamageTaken + $damageDealt, $squad->damage_taken);
//        $this->assertEquals($previousMinionDamageTaken + $damageDealt, $squad->minion_damage_taken);
//        $this->assertEquals($previousSideQuestDamageTaken + $damageDealt, $squad->side_quest_damage_taken);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_squads_damage_taken_from_minion_kills_hero_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->minionKillsHero(null,null, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $previousDamageTaken = $squad->damage_taken;
//        $previousMinionDamageTaken = $squad->minion_damage_taken;
//        $previousSideQuestDamageTaken = $squad->side_quest_damage_taken;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $squad = $squad->fresh();
//
//        $this->assertEquals($previousDamageTaken + $damageDealt, $squad->damage_taken);
//        $this->assertEquals($previousMinionDamageTaken + $damageDealt, $squad->minion_damage_taken);
//        $this->assertEquals($previousSideQuestDamageTaken + $damageDealt, $squad->side_quest_damage_taken);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_squad_death_counts_from_minion_kills_hero_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->minionKillsHero()
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $beforeSideQuestDeaths = $squad->side_quest_deaths;
//        $beforeMinionDeaths = $squad->minion_deaths;
//        $beforeCombatDeaths = $squad->combat_deaths;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $this->assertEquals($beforeSideQuestDeaths + 1, $squad->fresh()->side_quest_deaths);
//        $this->assertEquals($beforeMinionDeaths + 1, $squad->fresh()->minion_deaths);
//        $this->assertEquals($beforeCombatDeaths + 1, $squad->fresh()->combat_deaths);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_blocks_for_a_squad_for_hero_blocks_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroBlocksMinion()
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $beforeBlocks = $squad->attacks_blocked;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $this->assertEquals($beforeBlocks + 1, $squad->fresh()->attacks_blocked);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_a_squads_side_quest_victories_if_victory_event()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->sideQuestVictory()
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $previousVictories = $squad->side_quest_victories;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $this->assertEquals($previousVictories + 1, $squad->fresh()->side_quest_victories);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_a_squad_side_quest_defeats_if_defeated_event()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->sideQuestDefeat()
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $squad = $sideQuestResult->campaignStop->campaign->squad;
//
//        $previousVictories = $squad->side_quest_defeats;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $this->assertEquals($previousVictories + 1, $squad->fresh()->side_quest_defeats);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_items_damage_dealt_from_hero_damages_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $item = ItemFactory::new()->create();
//        $heroCombatAttack = HeroCombatAttackFactory::new()->forItem($item->uuid)->create();
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroDamagesMinion(null, $heroCombatAttack, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $beforeTotalDamage = $item->damage_dealt;
//        $beforeMinionDamageDealt = $item->minion_damage_dealt;
//        $beforeSideQuestDamageDealt = $item->side_quest_damage_dealt;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $item = $item->fresh();
//
//        $this->assertEquals($beforeTotalDamage + $damageDealt, $item->damage_dealt);
//        $this->assertEquals($beforeMinionDamageDealt + $damageDealt, $item->minion_damage_dealt);
//        $this->assertEquals($beforeSideQuestDamageDealt + $damageDealt, $item->side_quest_damage_dealt);
//    }
//
//
//    /**
//     * @test
//     */
//    public function it_will_increase_items_damage_dealt_from_hero_kills_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $item = ItemFactory::new()->create();
//        $heroCombatAttack = HeroCombatAttackFactory::new()->forItem($item->uuid)->create();
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroKillsMinion(null, $heroCombatAttack, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $beforeTotalDamage = $item->damage_dealt;
//        $beforeMinionDamageDealt = $item->minion_damage_dealt;
//        $beforeSideQuestDamageDealt = $item->side_quest_damage_dealt;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $item = $item->fresh();
//
//        $this->assertEquals($beforeTotalDamage + $damageDealt, $item->damage_dealt);
//        $this->assertEquals($beforeMinionDamageDealt + $damageDealt, $item->minion_damage_dealt);
//        $this->assertEquals($beforeSideQuestDamageDealt + $damageDealt, $item->side_quest_damage_dealt);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_increase_items_minion_kills_from_hero_kills_minion_events()
//    {
//        $sideQuestResult = $this->getValidSideQuestResult();
//
//        $damageDealt = rand(50, 1000);
//        $item = ItemFactory::new()->create();
//        $heroCombatAttack = HeroCombatAttackFactory::new()->forItem($item->uuid)->create();
//        $sideQuestEvent = SideQuestEventFactory::new()
//            ->heroKillsMinion(null, $heroCombatAttack, null, $damageDealt)
//            ->withSideQuestResultID($sideQuestResult->id)
//            ->create();
//
//        $previousKills = $item->combat_kills;
//        $previousMinionKills = $item->minion_kills;
//        $previousSideQuestKills = $item->side_quest_kills;
//
//        $this->getDomainAction()->execute($sideQuestResult);
//
//        $item = $item->fresh();
//
//        $this->assertEquals($previousKills + 1, $item->combat_kills);
//        $this->assertEquals($previousMinionKills + 1, $item->minion_kills);
//        $this->assertEquals($previousSideQuestKills + 1, $item->side_quest_kills);
//    }
}
