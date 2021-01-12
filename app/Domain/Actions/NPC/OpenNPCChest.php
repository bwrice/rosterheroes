<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\OpenChest;
use App\Domain\Models\Chest;
use App\Domain\Models\Squad;


/**
 * Class OpenNPCChest
 * @package App\Domain\Actions\NPC
 *
 * @method Chest execute(Squad $squad, ...$extra)
 */
class OpenNPCChest extends NPCAction
{
    protected OpenChest $openChest;

    public function __construct(OpenChest $openChest)
    {
        $this->openChest = $openChest;
    }

    public function handleExecute()
    {
        /** @var Chest $chest */
        $chest = $this->npc->chests()
            ->where('opened_at', '=', null)
            ->inRandomOrder()
            ->first();

        if (! $chest) {
            throw new \Exception("No unopened chest found for NPC");
        }

        return $this->openChest->execute($chest);
    }
}
