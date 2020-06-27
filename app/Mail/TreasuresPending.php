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

    public function __construct(Squad $squad, int $unopenedChestsCount)
    {
        $this->squad = $squad;
        $this->unopenedChestsCount = $unopenedChestsCount;
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
