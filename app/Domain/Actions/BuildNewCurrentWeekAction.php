<?php


namespace App\Domain\Actions;

use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Jobs\FinalizeWeekJob;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class BuildNewCurrentWeekAction
{
    /**
     * @return Week
     */
    public function execute(): Week
    {
        $now = Date::now();
        if ($now->dayOfWeek >= CarbonInterface::SATURDAY) {
            $startingPoint = $now->next(CarbonInterface::WEDNESDAY);
        } else {
            $startingPoint = $now;
        }

        $adventuringLocksAt = Week::computeAdventuringLocksAt($startingPoint);
        /** @var Week $newCurrentWeek */
        $newCurrentWeek = Week::query()->create([
            'uuid' => Str::uuid(),
            'adventuring_locks_at' => $adventuringLocksAt
        ]);

        $step = 1;
        $extraHoursToAdd = config('week.finalize_extra_hours_delay');
        $delay = WeekService::finalizingStartsAt($adventuringLocksAt)->clone()->addHours($extraHoursToAdd);
        FinalizeWeekJob::dispatch($step)->delay($delay);
        return $newCurrentWeek;
    }
}
