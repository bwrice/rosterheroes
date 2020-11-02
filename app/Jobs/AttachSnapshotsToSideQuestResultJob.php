<?php

namespace App\Jobs;

use App\Domain\Actions\AttachSnapshotsToSideQuestResult;
use App\Domain\Models\SideQuestResult;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachSnapshotsToSideQuestResultJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SideQuestResult $sideQuestResult;

    public function __construct(SideQuestResult $sideQuestResult)
    {
        $this->sideQuestResult = $sideQuestResult;
    }

    /**
     * @param AttachSnapshotsToSideQuestResult $attachSnapshotsToSideQuestResult
     * @throws \Exception
     */
    public function handle(AttachSnapshotsToSideQuestResult $attachSnapshotsToSideQuestResult)
    {
        $attachSnapshotsToSideQuestResult->execute($this->sideQuestResult);
    }
}
