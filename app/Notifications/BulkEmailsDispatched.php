<?php

namespace App\Notifications;

use App\Domain\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class BulkEmailsDispatched extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     */
    protected $emailName;
    /**
     * @var int
     */
    protected $usersCount;

    public function __construct(string $emailName, int $usersCount)
    {
        $this->emailName = $emailName;
        $this->usersCount = $usersCount;
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
            ->from(config('app.name'), ':mailbox:')
            ->content("Emails Dispatched")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'email type' => $this->emailName,
                    'users count' => $this->usersCount
                ]);
            });
    }
}
