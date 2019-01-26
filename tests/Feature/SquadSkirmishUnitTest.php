<?php

namespace Tests\Feature;

use App\Skirmish;
use App\Squad;
use App\Weeks\Week;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadSkirmishUnitTest extends TestCase
{
//    /**
//     * @test
//     */
//    public function adding_a_skirmish_without_joining_the_quest_will_throw_an_exception()
//    {
//        /** @var Skirmish $skirmish */
//        $skirmish = factory(Skirmish::class)->create();
//
//        /** @var Squad $squad */
//        $squad = factory(Squad::class)->create();
//
//        /** @var Week $week */
//        $week = factory(Week::class)->create();
//        Week::setTestCurrent($week);
//        Carbon::setTestNow($week->everything_locks_at->copy()->subDays(1));
//
//        try {
//
//        } catch ( \Exception $exception ) {
//
//        }
//    }
//
//    /**
//     * @test
//     */
//    public function adding_a_skirmish_when_a_week_is_locked_will_throw_an_exception()
//    {
//        $this->fail();
//    }
//
//    /**
//     * @test
//     */
//    public function adding_a_skirmish_after_the_squad_quest_skirmish_limit_is_reached_will_throw_an_exception()
//    {
//        $this->fail();
//    }
}
