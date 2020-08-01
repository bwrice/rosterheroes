<?php

namespace App\Mail;

use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlmostReadyForWeek extends SquadNotification
{
    use Queueable, SerializesModels;
    /**
     * @var Squad
     */
    public $squad;
    /**
     * @var Week
     */
    public $week;

    /**
     * AlmostReadyForWeek constructor.
     * @param Squad $squad
     * @param Week $week
     */
    public function __construct(Squad $squad, Week $week)
    {
        parent::__construct();
        $this->squad = $squad;
        $this->week = $week;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
