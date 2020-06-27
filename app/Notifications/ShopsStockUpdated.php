<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ShopsStockUpdated extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * @var int
     */
    protected $shopsCount;

    public function __construct(int $shopsCount)
    {
        $this->shopsCount = $shopsCount;
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
            ->from(config('app.name'), ':thumbsup:')
            ->content("Shops stock updated")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'shops count' => $this->shopsCount
                ]);
            });
    }
}
