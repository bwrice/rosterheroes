<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ManageNPCJobsDispatched extends Notification implements ShouldQueue
{
    use Queueable;

    public int $jobsCount;
    public ?array $actions;

    /**
     * ManageNPCJobsDispatched constructor.
     * @param int $jobsCount
     * @param array|null $actions
     */
    public function __construct(int $jobsCount, ?array $actions)
    {
        //
        $this->jobsCount = $jobsCount;
        $this->actions = $actions;
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
            ->from(config('app.name'), ':crystal_ball:')
            ->content("Shops stock updated")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'jobs count' => $this->jobsCount,
                    'actions' => $this->actions
                ]);
            });
    }
}
