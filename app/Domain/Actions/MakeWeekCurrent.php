<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;

class MakeWeekCurrent
{
    /**
     * @param Week $week
     * @return Week|null
     * @throws \Exception
     */
    public function execute(Week $week)
    {
        if ($week->made_current_at) {
            throw new \Exception("Week: " . $week->id . " already made current");
        }

        $week->made_current_at = now();
        $week->save();
        return $week->fresh();
    }
}
