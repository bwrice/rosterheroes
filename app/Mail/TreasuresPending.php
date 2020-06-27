<?php

namespace App\Mail;

use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TreasuresPending extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'You Have Treasures Chests Waiting To Be Opened!';

    /**
     * @var Squad
     */
    public $squad;
    /**
     * @var int
     */
    public $unopenedChestsCount;

    public $title = '';

    public $message = '';

    public function __construct(Squad $squad, int $unopenedChestsCount)
    {
        $this->squad = $squad;
        $this->unopenedChestsCount = $unopenedChestsCount;
        $this->title = $this->buildTitle();
        $this->message = $this->buildMessage();
    }

    protected function buildTitle()
    {
        if ($this->unopenedChestsCount === 1) {
            return 'Treasure Chest Awaiting!';
        }
        return $this->unopenedChestsCount . ' Unopened Chests Awaiting!';
    }

    protected function buildMessage()
    {
        $message = "Your squad, " . $this->squad->name . ', ';
        if ($this->unopenedChestsCount === 1) {
            $message .= "has a treasure chest that was earned from a campaign and still hasn't been opened. ";
        } else {
            $message .= "has " . $this->unopenedChestsCount . " treasure chests that were earned from campaigns and still haven't been opened. ";
        }
        $message .= "There's loot to be had!. Visit " . $this->squad->name . "'s command center and " ;

        if ($this->unopenedChestsCount === 1) {
            $message .= "open that chest!";
        } else {
            $message .= "open those chests!";
        }

        return $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.treasures-pending');
    }
}
