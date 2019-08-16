<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\HeroPlayerSpiritException;

class RemoveSpiritFromHeroAction extends HeroSpiritAction
{
    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    public function execute(Hero $hero, PlayerSpirit $playerSpirit): Hero
    {
        $this->validateEmbodiedBy($hero, $playerSpirit);
        $this->validateWeek($hero, $playerSpirit);
        $this->validateGameTime($hero, $playerSpirit);
        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($hero->uuid);
        $heroAggregate->updatePlayerSpirit(null)->persist();
        return $hero->fresh();
    }

    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @throws HeroPlayerSpiritException
     */
    protected function validateEmbodiedBy(Hero $hero, PlayerSpirit $playerSpirit)
    {
        if ($hero->player_spirit_id !== $playerSpirit->id) {
            $message = $hero->name . " is not embodied by " . $playerSpirit->player->fullName();
            throw new HeroPlayerSpiritException(
                $hero,
                $playerSpirit,
                $message,
                HeroPlayerSpiritException::NOT_EMBODIED_BY
            );
        }
    }
}
