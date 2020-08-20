<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Squad;
use App\Facades\NPC;

abstract class NPCAction
{
    public const EXCEPTION_CODE_NOT_NPC = 1;

    /** @var Squad */
    protected $npc;

    /**
     * @param Squad $squad
     * @param mixed ...$extra
     * @throws \Exception
     */
    public function execute(Squad $squad, ...$extra)
    {
        if (! NPC::isNPC($squad)) {
            throw new \Exception($squad->name . " is not an NPC", self::EXCEPTION_CODE_NOT_NPC);
        }

        $this->npc = $squad;

        call_user_func([$this, 'handleExecute'], ...$extra);
    }
}
