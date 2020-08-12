<?php

namespace App\Mail;

use App\Domain\Models\EmailSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class SquadNotification extends Mailable
{
    use Queueable, SerializesModels;

    /** @var EmailSubscription */
    public $emailSub;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->emailSub = EmailSubscription::squadNotifications();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    abstract public function build();
}
