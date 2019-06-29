<?php

namespace App\Jobs;

use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BuildNextWeekJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @return Week
     * @throws \Exception
     */
    public function handle()
    {
        $week = Week::getLatest();
        if ($week) {
            $nextWeek = Week::query()->create([
                'uuid' => (string) \Ramsey\Uuid\Uuid::uuid4(),
                'proposals_scheduled_to_lock_at' => $week->proposals_scheduled_to_lock_at->addWeek(),
                'diplomacy_scheduled_to_lock_at' => $week->diplomacy_scheduled_to_lock_at->addWeek(),
                'everything_locks_at' => $week->everything_locks_at->addWeek(),
                'ends_at' => $week->ends_at->addWeek()
            ]);
        } else {
            $nextWeek = Week::makeForNow();
            $nextWeek->save();
        }
        return $nextWeek;
    }
}
