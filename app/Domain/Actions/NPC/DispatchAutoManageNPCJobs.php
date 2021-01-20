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
    public function execute($minutesDelayMax = 60)
    {
        $now = now();
        /** @var CarbonInterface $eastCoastNow */
        $eastCoastNow = $now->setTimezone('America/New_York');
        $triggerChance = $this->getTriggerChance($eastCoastNow);
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

    /**
     * @param CarbonInterface $eastCoastDateTime
     * @return float|int
     */
    protected function getTriggerChance(CarbonInterface $eastCoastDateTime)
    {
        // Start with .2% chance to trigger any actions
        $triggerChance = .2;
        $triggerChance = $this->adjustTriggerChanceForDayOfWeek($triggerChance, $eastCoastDateTime->dayOfWeek);
        return $this->adjustTriggerChanceForHourOfDay($triggerChance, $eastCoastDateTime->hour);
    }

    protected function adjustTriggerChanceForDayOfWeek(float $triggerChance, int $dayOfWeek)
    {
        $multiplier = 1;
        switch ($dayOfWeek) {
            case 0: // Sunday
                $multiplier = 5;
                break;
            case 1: // Monday
                $multiplier = 4;
                break;
            case 2: // Tuesday
                $multiplier = 3;
                break;
            case 6: // Saturday
                $multiplier = 2;
                break;
        }
        return $triggerChance * $multiplier;
    }

    protected function adjustTriggerChanceForHourOfDay(float $triggerChance, int $hourOfDay)
    {
        $multiplier = 1;
        switch ((int) floor($hourOfDay/3)) {
            case 0: // 0:00 to 2:59 AM
            case 1: // 3:00 to 5:59 AM
                $multiplier = 1;
                break;
            case 2: // 6:00 to 8:59 AM
                $multiplier = 2;
                break;
            case 3: // 9:00 to 11:59 AM
                $multiplier = 6;
                break;
            case 4: // 12:00 to 2:59 PM
                $multiplier = 4;
                break;
            case 5: // 3:00 to 5:59 PM
                $multiplier = 2;
                break;
            case 6: // 6:00 to 8:59 PM
                $multiplier = 6;
                break;
            case 7: // 9:00 to 11:59 PM
                $multiplier = 8;
                break;
        }
        return $triggerChance * $multiplier;
    }

}
