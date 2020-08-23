<?php


namespace App\Domain\Actions\NPC;

use App\Domain\Models\Hero;
use App\Facades\NPC;

abstract class NPCHeroAction
{
    public const EXCEPTION_CODE_NOT_NPC = 1;

    /** @var Hero */
    protected $hero;

    /**
     * @param Hero $hero
     * @param mixed ...$extra
     * @return mixed
     * @throws \Exception
     */
    public function execute(Hero $hero, ...$extra)
    {
        if (! NPC::isNPC($hero->squad)) {
            throw new \Exception($hero->squad->name . " is not an NPC", self::EXCEPTION_CODE_NOT_NPC);
        }

        $this->hero = $hero;

        return call_user_func([$this, 'handleExecute'], ...$extra);
    }
}
