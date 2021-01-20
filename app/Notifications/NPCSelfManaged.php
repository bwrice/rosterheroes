<?php

namespace App\Notifications;

use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NPCSelfManaged extends Notification implements ShouldQueue
{
    use Queueable;

    public Squad $npc;
    public float $triggerChance;
    public array $jobsGroups;

    public function __construct(Squad $npc, float $triggerChance, array $jobGroups)
    {
        $this->npc = $npc;
        $this->triggerChance = $triggerChance;
        $this->jobsGroups = $jobGroups;
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
            ->from(config('app.name'), ':moyai:')
            ->content("NPC Self Managed")
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields(array_merge([
                    'name' => $this->npc->name,
                    'trigger chance' => $this->triggerChance . '%'
                ], $this->jobsGroups));
            });
    }
}
