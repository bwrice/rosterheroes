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

        $adventuringLocksAt = $this->getAdventuringLocksAt();
        /** @var Week $week */
        $week = Week::query()->create([
            'uuid' => Str::uuid(),
            'adventuring_locks_at' => $adventuringLocksAt
        ]);
        return $week;
    }

    protected function getAdventuringLocksAt()
    {
        $sunday = $this->startingPoint->next(CarbonInterface::SUNDAY)->setTimezone('America/New_York');
        $offset = $sunday->getOffset();
        return $sunday->addHours(13)->subSeconds($offset)->setTimezone('UTC');
    }
}
