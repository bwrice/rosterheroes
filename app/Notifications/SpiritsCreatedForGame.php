<?php

namespace App\Notifications;

use App\Domain\Models\Game;
use App\Domain\Models\Week;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SpiritsCreatedForGame extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Game
     */
    public $game;
    /**
     * @var string
     */
    public $reason;

    public function __construct(Game $game)
    {
        $this->game = $game;
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
            ->from(config('app.name'), ':exclamation:')
            ->content("Spirits Created for Game")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'home team' => $this->game->homeTeam->location . ' ' . $this->game->homeTeam->name,
                    'away team' => $this->game->awayTeam->location . ' ' . $this->game->awayTeam->name,
                    'game time' => $this->game->starts_at->clone()->timezone("America/New_York")->toDayDateTimeString()
                ]);
            });
    }
}
