<?php


namespace App\Admin\Content\Sources;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Minion;

class MinionSource
{
    /**
     * @var string
     */
    protected $uuid;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $level;
    /**
     * @var int
     */
    protected $enemyTypeID;
    /**
     * @var int
     */
    protected $combatPositionID;
    /**
     * @var array
     */
    protected $attacks;
    /**
     * @var array
     */
    protected $chestBlueprints;

    public function __construct(
        string $uuid,
        string $name,
        int $level,
        int $enemyTypeID,
        int $combatPositionID,
        array $attacks,
        array $chestBlueprints
    )
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->level = $level;
        $this->enemyTypeID = $enemyTypeID;
        $this->combatPositionID = $combatPositionID;
        $this->attacks = $attacks;
        $this->chestBlueprints = $chestBlueprints;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getEnemyTypeID(): int
    {
        return $this->enemyTypeID;
    }

    /**
     * @return int
     */
    public function getCombatPositionID(): int
    {
        return $this->combatPositionID;
    }

    /**
     * @return array
     */
    public function getAttacks(): array
    {
        return $this->attacks;
    }

    /**
     * @return array
     */
    public function getChestBlueprints(): array
    {
        return $this->chestBlueprints;
    }

    public function isSynced(Minion $minion)
    {
        if ($minion->name !== $this->getName()) {
            return false;
        }
        if ($minion->level !== $this->getLevel()) {
            return false;
        }
        if ($minion->enemy_type_id !== $this->getEnemyTypeID()) {
            return false;
        }
        if ($minion->combat_position_id !== $this->getCombatPositionID()) {
            return false;
        }
        if ($minion->name !== $this->getName()) {
            return false;
        }

        if ($minion->attacks->pluck('uuid')->toArray() !== $this->getAttacks()) {
            return false;
        }

        $chestBlueprintArrays = collect($this->getChestBlueprints());
        if ($minion->chestBlueprints->count() !== count($chestBlueprintArrays)) {
            return false;
        }

        $unSyncedChestBlueprint = $minion->chestBlueprints->first(function (ChestBlueprint $chestBlueprint) use ($chestBlueprintArrays) {
            $matchingBlueprintArray = $chestBlueprintArrays->first(function ($itemBlueprintArray) use ($chestBlueprint) {
                return $itemBlueprintArray['uuid'] === (string) $chestBlueprint->uuid;
            });
            if (! $matchingBlueprintArray) {
                return true;
            }

            $unSynced = $matchingBlueprintArray['count'] !== $chestBlueprint->pivot->count
                // Comparing floats...
                || abs($chestBlueprint->pivot->chance - $matchingBlueprintArray['chance']) > PHP_FLOAT_EPSILON;

            return $unSynced;
        });

        return is_null($unSyncedChestBlueprint);
    }
}
