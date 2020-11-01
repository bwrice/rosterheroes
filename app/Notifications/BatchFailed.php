<?php

namespace App\Notifications;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class BatchFailed extends Notification
{
    use Queueable;

    protected Batch $batch;
    protected \Throwable $exception;

    public function __construct(Batch $batch, \Throwable $exception)
    {
        $this->batch = $batch;
        $this->exception = $exception;
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
        return (new SlackMessage)
            ->info()
            ->from(config('app.name'), ':bangbang:')
            ->content("Batch: " . $this->batch->name . " failed with exception: " . $this->exception->getMessage())
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'progress' => $this->batch->progress(),
                    'pending jobs' => $this->batch->pendingJobs
                ]);
            });
    }
}
