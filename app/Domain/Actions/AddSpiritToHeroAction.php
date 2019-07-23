<?php


namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\NotEnoughEssenceException;

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

    public function __invoke(): Hero
    {
        if (! Week::isCurrent($this->playerSpirit->week)) {
            throw new InvalidWeekException($this->playerSpirit->week);
        }

        if (! $this->hero->heroRace->positions->intersect($this->playerSpirit->getPositions())->count() > 0 ) {
            $exception = new InvalidPositionsException();
            $exception->setPositions($this->hero->heroRace->positions, $this->playerSpirit->getPositions());
            throw $exception;
        }

        if (! $this->heroCanAffordSpirit()) {
            $exception = new NotEnoughEssenceException();
            $exception->setAttributes($this->hero->availableEssence(), $this->playerSpirit->essence_cost);
            throw $exception;
        }

        if ($this->playerSpirit->game->hasStarted()) {
            $exception = new GameStartedException();
            $exception->setGame($this->playerSpirit->game);
            throw $exception;
        }

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
}