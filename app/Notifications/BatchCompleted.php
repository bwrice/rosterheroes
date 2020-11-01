<?php

namespace App\Notifications;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class BatchCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected Batch $batch;

    public function __construct(Batch $batch)
    {
        $this->batch = $batch;
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
        $milliseconds = $this->batch->finishedAt->diffInMilliseconds($this->batch->createdAt);
        return (new SlackMessage)
            ->info()
            ->from(config('app.name'), ':package:')
            ->content("Batch: " . $this->batch->name . " completed")
            ->attachment(function (SlackAttachment $attachment) use ($milliseconds) {
                $attachment->fields([
                    'total jobs' => $this->batch->totalJobs,
                    'seconds' => round($milliseconds/1000, 3)
                ]);
            });
    }
}
