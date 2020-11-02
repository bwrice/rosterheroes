<?php

namespace App\Notifications;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Bus;

class BatchFailed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $batchID;
    protected string $message;

    public function __construct($batchID, string $message)
    {
        $this->batchID = $batchID;
        $this->message = $message;
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
        return (new SlackMessage)
            ->info()
            ->from(config('app.name'), ':bangbang:')
            ->content("Batch: " . $batch->name . " failed with exception message: " . $this->message)
            ->attachment(function (SlackAttachment $attachment) use ($batch) {
                $attachment->fields([
                    'progress' => $batch->progress(),
                    'pending jobs' => $batch->pendingJobs
                ]);
            });
    }
}
