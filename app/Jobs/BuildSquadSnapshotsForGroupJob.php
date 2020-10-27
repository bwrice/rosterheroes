<?php

namespace App\Jobs;

use App\Domain\Actions\Snapshots\BuildSquadSnapshot;
use App\Domain\Models\Squad;
use App\Facades\CurrentWeek;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildSquadSnapshotsForGroupJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public int $startRangeID;
    public int $endRangeID;

    public function __construct(int $startRangeID, int $endRangeID)
    {
        $this->startRangeID = $startRangeID;
        $this->endRangeID = $endRangeID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $jobs = Squad::query()->whereIn('id', [$this->startRangeID, $this->endRangeID])
            ->whereHas('campaigns', function (Builder $builder) {
                $builder->where('week_id', '=', CurrentWeek::id());
            })->get()->map(function (Squad $squad) {
                return new BuildSquadSnapshotJob($squad);
            });

        $this->batch()->add($jobs);
    }
}
