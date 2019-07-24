<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\HeroPlayerSpiritException;

class AddSpiritToHeroAction
{
    /**
     * @var Hero
     */
    private $hero;
    /**
     * @var PlayerSpirit
     */
    private $playerSpirit;

    public function __construct(Hero $hero, PlayerSpirit $playerSpirit)
    {
        $this->hero = $hero;
        $this->playerSpirit = $playerSpirit;
    }

    /**
     * @return Hero
     * @throws HeroPlayerSpiritException
     */
    public function __invoke(): Hero
    {
        $this->validateWeek();
        $this->validatePositions();
        $this->validateEssenceCost();
        $this->validateGameTime();

        /** @var HeroAggregate $heroAggregate */
        $heroAggregate = HeroAggregate::retrieve($this->hero->uuid);
        $heroAggregate->updatePlayerSpirit($this->playerSpirit->id)->persist();

        return $this->hero->fresh();
    }

    /**
     * @return bool
     */
    protected function heroCanAffordSpirit(): bool
    {
        return ($this->hero->availableEssence() >= $this->playerSpirit->essence_cost);
    }

    /**
     * @throws HeroPlayerSpiritException
     */
    protected function validateWeek(): void
    {
        if (!Week::isCurrent($this->playerSpirit->week)) {
            $gameDescription = $this->playerSpirit->game->getSimpleDescription();
            $message = $gameDescription . ' is not for the current week';
            throw new HeroPlayerSpiritException(
                $this->hero,
                $this->playerSpirit,
                $message,
                HeroPlayerSpiritException::INVALID_WEEK
            );
        }
    }

    /**
     * @throws HeroPlayerSpiritException
     */
    protected function validatePositions(): void
    {
        if (!$this->hero->heroRace->positions->intersect($this->playerSpirit->getPositions())->count() > 0) {
            $playerName = $this->playerSpirit->player->fullName();
            $heroRaceName = ucwords(str_replace('-', ' ', $this->hero->heroRace->name));
            $message = $heroRaceName . " does not have valid positions for " . $playerName;
            throw new HeroPlayerSpiritException(
                $this->hero,
                $this->playerSpirit,
                $message,
                HeroPlayerSpiritException::INVALID_PLAYER_POSITIONS);
        }
    }

    /**
     * @throws HeroPlayerSpiritException
     */
    protected function validateEssenceCost(): void
    {
        if (!$this->heroCanAffordSpirit()) {
            $message = $this->hero->availableEssence() . " essence available, but " . $this->playerSpirit->essence_cost . "needed";
            throw new HeroPlayerSpiritException(
                $this->hero,
                $this->playerSpirit,
                $message,
                HeroPlayerSpiritException::NOT_ENOUGH_ESSENCE
            );
        }
    }

    /**
     * @throws HeroPlayerSpiritException
     */
    protected function validateGameTime(): void
    {
        if ($this->playerSpirit->game->hasStarted()) {
            $message = $this->playerSpirit->game->getSimpleDescription() . " has already started";
            throw new HeroPlayerSpiritException(
                $this->hero,
                $this->playerSpirit,
                $message,
                HeroPlayerSpiritException::GAME_STARTED
            );
        }
    }
}