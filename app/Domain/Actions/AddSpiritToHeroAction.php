<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\HeroPlayerSpiritException;

class AddSpiritToHeroAction extends HeroSpiritAction
{
    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    public function execute(Hero $hero, PlayerSpirit $playerSpirit): Hero
    {
        $this->validateWeek($hero, $playerSpirit);
        $this->validatePositions($hero, $playerSpirit);
        $this->validateEssenceCost($hero, $playerSpirit);
        $this->validateGameTime($hero, $playerSpirit);

        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($hero->uuid);
        $heroAggregate->updatePlayerSpirit($playerSpirit->id)->persist();

        return $hero->fresh();
    }

    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @return bool
     */
    protected function heroCanAffordSpirit(Hero $hero, PlayerSpirit $playerSpirit): bool
    {
        return ($hero->availableEssence() >= $playerSpirit->essence_cost);
    }

    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @throws HeroPlayerSpiritException
     */
    protected function validatePositions(Hero $hero, PlayerSpirit $playerSpirit): void
    {
        if (!$hero->heroRace->positions->intersect($playerSpirit->getPositions())->count() > 0) {
            $playerName = $playerSpirit->player->fullName();
            $heroRaceName = ucwords(str_replace('-', ' ', $hero->heroRace->name));
            $message = $heroRaceName . " does not have valid positions for " . $playerName;
            throw new HeroPlayerSpiritException(
                $hero,
                $playerSpirit,
                $message,
                HeroPlayerSpiritException::INVALID_PLAYER_POSITIONS);
        }
    }

    /**
     * @param Hero $hero
     * @param PlayerSpirit $playerSpirit
     * @throws HeroPlayerSpiritException
     */
    protected function validateEssenceCost(Hero $hero, PlayerSpirit $playerSpirit): void
    {
        if (! $this->heroCanAffordSpirit($hero, $playerSpirit)) {
            $message = $hero->availableEssence() . " essence available, but " . $playerSpirit->essence_cost . "needed";
            throw new HeroPlayerSpiritException(
                $hero,
                $playerSpirit,
                $message,
                HeroPlayerSpiritException::NOT_ENOUGH_ESSENCE
            );
        }
    }
}