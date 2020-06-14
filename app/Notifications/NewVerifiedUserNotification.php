<?php

namespace App\Notifications;

use App\Domain\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewVerifiedUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
            ->success()
            ->from(config('app.name'), ':tada:')
            ->content("A new user has been verified!")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ]);
            });
    }
}
