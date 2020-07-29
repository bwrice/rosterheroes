<?php

namespace App\Notifications;

use App\Domain\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SpiritsDisabledForGame extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Game
     */
    public $game;
    /**
     * @var int
     */
    public $spiritsRemoved;
    /**
     * @var int
     */
    public $heroesCleared;
    /**
     * @var string
     */
    public $reason;

    public function __construct(Game $game, int $spiritsRemoved, int $heroesCleared, string $reason = 'N/A')
    {
        $this->game = $game;
        $this->spiritsRemoved = $spiritsRemoved;
        $this->heroesCleared = $heroesCleared;
        $this->reason = $reason;
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
            ->content("Spirits Disabled for Game")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'home team' => $this->game->homeTeam->location . ' ' . $this->game->homeTeam->name,
                    'away team' => $this->game->awayTeam->location . ' ' . $this->game->awayTeam->name,
                    'reason' => $this->reason,
                    'game time' => $this->game->starts_at->clone()->timezone("America/New_York")->toDayDateTimeString(),
                    'spirits removed' => $this->spiritsRemoved,
                    'heroes cleared' => $this->heroesCleared
                ]);
            });
    }
}
