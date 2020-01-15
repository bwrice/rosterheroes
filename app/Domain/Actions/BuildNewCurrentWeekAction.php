<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class BuildNewCurrentWeekAction
{
    public function execute(): Week
    {
        $now = Date::now();
        if ($now->dayOfWeek >= CarbonInterface::WEDNESDAY) {
            $startingPoint = $now->next(CarbonInterface::TUESDAY);
        } else {
            $startingPoint = $now;
        }

        $adventuringLocksAt = Week::computeAdventuringLocksAt($startingPoint);
        /** @var Week $week */
        $week = Week::query()->create([
            'uuid' => Str::uuid(),
            'adventuring_locks_at' => $adventuringLocksAt,
            'made_current_at' => $now
        ]);
        return $week;
    }
}
