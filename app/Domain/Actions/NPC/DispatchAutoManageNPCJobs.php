<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Squad;
use App\Facades\Admin;
use App\Facades\CurrentWeek;
use App\Facades\NPC;
use App\Jobs\AutoManageNPCJob;
use App\Notifications\ManageNPCJobsDispatched;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;

class DispatchAutoManageNPCJobs
{
    const DEFAULT_TRIGGER_CHANCE_BY_HOUR = [
        0 => 0.25,
        1 => 0.25,
        2 => 0.25,
        3 => 0.5,
        4 => 0.75,
        5 => 1,
        6 => 1,
        7 => 1.5,
        8 => 2,
        9 => 2.5,
        10 => 3,
        11 => 3,
        12 => 3,
        13 => 3,
        14 => 3,
        15 => 3,
        16 => 2,
        17 => 2,
        18 => 3,
        19 => 3.5,
        20 => 3,
        21 => 2,
        22 => 1,
        23 => 0.75
    ];

    public function execute($minutesDelayMax = 60)
    {
        $now = now();
        /** @var CarbonInterface $eastCoastNow */
        $eastCoastNow = $now->setTimezone('America/New_York');
        $triggerChanceArray = $this->triggerChanceArray();
        $triggerChance = $triggerChanceArray[$eastCoastNow->dayOfWeek][$eastCoastNow->hour];
        $actions = CurrentWeek::adventuringLocked() ? AutoManageNPC::ADVENTURING_LOCKED_ACTIONS : AutoManageNPC::DEFAULT_ACTIONS;

        $jobsCount = 0;
        NPC::user()->squads()->chunk(100, function (Collection $npcSquads) use ($triggerChance, $minutesDelayMax, $now, $actions, &$jobsCount) {

            $npcSquads->each(function (Squad $npc) use ($triggerChance, $minutesDelayMax, $now, $actions) {
                /** @var CarbonInterface $delay */
                $delay = $now->addMinutes(rand(2, $minutesDelayMax));
                $job = (new AutoManageNPCJob($npc, $triggerChance, $actions))->delay($delay);
                dispatch($job);
            });

            $jobsCount += $npcSquads->count();
        });

        Admin::notify(new ManageNPCJobsDispatched($jobsCount, $actions));
    }

    protected function triggerChanceArray()
    {
        return [
            // Sunday
            0 => [
                0 => 0.5,
                1 => 0.5,
                2 => 0.5,
                3 => 0.5,
                4 => 1,
                5 => 1,
                6 => 2,
                7 => 3,
                8 => 5,
                9 => 8,
                10 => 9,
                11 => 10,
                12 => 3,
                13 => 3,
                14 => 2.5,
                15 => 2,
                16 => 2,
                17 => 2,
                18 => 2,
                19 => 1.5,
                20 => 1,
                21 => 1,
                22 => .75,
                23 => .75
            ],
            // Monday
            1 => [
                0 => 0.25,
                1 => 0.25,
                2 => 0.25,
                3 => 0.25,
                4 => 0.5,
                5 => 1,
                6 => 2,
                7 => 3,
                8 => 5,
                9 => 6,
                10 => 7,
                11 => 6,
                12 => 5,
                13 => 5,
                14 => 4,
                15 => 4,
                16 => 5,
                17 => 6,
                18 => 6,
                19 => 5,
                20 => 2,
                21 => 2,
                22 => 1,
                23 => 1
            ],
            // Tuesday
            2 => self::DEFAULT_TRIGGER_CHANCE_BY_HOUR,
            // Wednesday
            3 => self::DEFAULT_TRIGGER_CHANCE_BY_HOUR,
            // Thursday
            4 => self::DEFAULT_TRIGGER_CHANCE_BY_HOUR,
            // Friday (+50% default trigger chance)
            5 => array_map(function ($chance) {
                return $chance * 1.5;
            }, self::DEFAULT_TRIGGER_CHANCE_BY_HOUR),
            // Saturday (+100% default trigger chance)
            6 => array_map(function ($chance) {
                return $chance * 2;
            }, self::DEFAULT_TRIGGER_CHANCE_BY_HOUR)
        ];
    }

}
