<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SpiritEnergiesUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var int
     */
    protected $secondsToProcess;
    /**
     * @var int
     */
    protected $spiritsWithHeroesCount;
    /**
     * @var int
     */
    protected $lowestEnergy;
    /**
     * @var int
     */
    protected $highestEnergy;

    public function __construct(int $secondsToProcess, int $spiritsWithHeroesCount, int $lowestEnergy, int $highestEnergy)
    {
        $this->secondsToProcess = $secondsToProcess;
        $this->spiritsWithHeroesCount = $spiritsWithHeroesCount;
        $this->lowestEnergy = $lowestEnergy;
        $this->highestEnergy = $highestEnergy;
    }

    public function via()
    {
        return ['slack'];
    }

    public function toSlack()
    {
        return (new SlackMessage)
            ->success()
            ->from(config('app.name'), ':ghost:')
            ->content("Spirit energies updated in " . $this->secondsToProcess . " seconds.")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'spirits_with_heroes_count' => $this->spiritsWithHeroesCount,
                    'lowest_energy' => $this->lowestEnergy,
                    'highest_energy' => $this->highestEnergy
                ]);
            });
    }
}
