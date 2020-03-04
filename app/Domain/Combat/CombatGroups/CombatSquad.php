<?php


namespace App\Domain\Combat\CombatGroups;


use App\Domain\Collections\CombatantCollection;
use App\Domain\Collections\AbstractCombatantCollection;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class CombatSquad implements CombatGroup, Arrayable
{
    /**
     * @var string
     */
    protected $squadName;
    /**
     * @var string
     */
    protected $squadUuid;
    /**
     * @var int
     */
    protected $experience;
    /**
     * @var AbstractCombatantCollection
     */
    protected $combatHeroes;

    public function __construct(string $squadName, string $squadUuid, int $experience, AbstractCombatantCollection $combatHeroes)
    {
        $this->squadName = $squadName;
        $this->squadUuid = $squadUuid;
        $this->experience = $experience;
        $this->combatHeroes = $combatHeroes;
    }

    public function getReadyAttacks(int $moment): Collection
    {
        return $this->combatHeroes->getReadyAttacks();
    }

    /**
     * @param $moment
     * @return AbstractCombatantCollection
     */
    public function getPossibleTargets($moment): CombatantCollection
    {
        return $this->combatHeroes->getPossibleTargets();
    }

    /**
     * @return string
     */
    public function getSquadUuid(): string
    {
        return $this->squadUuid;
    }

    /**
     * @return int
     */
    public function getExperience(): int
    {
        return $this->experience;
    }

    /**
     * @return AbstractCombatantCollection
     */
    public function getCombatHeroes(): AbstractCombatantCollection
    {
        return $this->combatHeroes;
    }

    public function isDefeated()
    {
        return ! $this->combatHeroes->hasSurvivors();
    }


    public function updateCombatPositions(CombatPositionCollection $combatPositions)
    {
        $this->combatHeroes->updateCombatPositions($combatPositions);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'squadName' => $this->squadName,
            'squadUuid' => $this->squadUuid,
            'experience' => $this->experience,
            'combatHeroes' => $this->combatHeroes->toArray()
        ];
    }
}
