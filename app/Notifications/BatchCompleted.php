<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Bus;

class BatchCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $batchID;

    public function __construct($batchID)
    {
        $this->batchID = $batchID;
    }


    public function via()
    {
        return ['slack'];
    }

    /**
     * @return SlackMessage
     */
    public function toSlack()
    {
        $batch = Bus::findBatch($this->batchID);
        $milliseconds = $batch->finishedAt->diffInMilliseconds($batch->createdAt);
        return (new SlackMessage)
            ->info()
            ->from(config('app.name'), ':package:')
            ->content("Batch: " . $batch->name . " completed")
            ->attachment(function (SlackAttachment $attachment) use ($batch, $milliseconds) {
                $attachment->fields([
                    'total jobs' => $batch->totalJobs,
                    'seconds' => round($milliseconds/1000, 3)
                ]);
            });
    }
}
