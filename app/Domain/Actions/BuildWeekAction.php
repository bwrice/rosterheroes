<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class BuildWeekAction
{
    /** @var CarbonImmutable */
    protected $startingPoint;

    public function execute(CarbonInterface $startingPoint): Week
    {
        if ($startingPoint->dayOfWeek >= CarbonInterface::WEDNESDAY) {
            $this->startingPoint = $startingPoint->toImmutable()->next(CarbonInterface::TUESDAY);
        } else {
            $this->startingPoint = $startingPoint->toImmutable();
        }

        $adventuringLocksAt = Week::computeAdventuringLocksAt($this->startingPoint);
        /** @var Week $week */
        $week = Week::query()->create([
            'uuid' => Str::uuid(),
            'adventuring_locks_at' => $adventuringLocksAt
        ]);
        return $week;
    }
}
