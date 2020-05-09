<?php

namespace App;

use App\Domain\Combat\Combatants\CombatHeroDataMapper;
use App\Domain\Combat\Combatants\CombatMinionDataMapper;
use App\Domain\Models\EventSourcedModel;
use App\Domain\QueryBuilders\SideQuestEventQueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SideQuestEvent
 * @package App
 *
 * @property array $data
 * @property string $uuid
 * @property string $event_type
 * @property int $moment
 *
 * @property SideQuestResult $sideQuestResult
 */
class SideQuestEvent extends EventSourcedModel
{
    public const TYPE_BATTLEGROUND_SET = 'battleground-set';
    public const TYPE_HERO_DAMAGES_MINION = 'hero-damages-minion';
    public const TYPE_HERO_KILLS_MINION = 'hero-kills-minion';
    public const TYPE_MINION_BLOCKS_HERO = 'minion-blocks-hero';
    public const TYPE_MINION_DAMAGES_HERO = 'minion-damages-hero';
    public const TYPE_MINION_KILLS_HERO = 'minion-kills-hero';
    public const TYPE_HERO_BLOCKS_MINION = 'hero-blocks-minion';
    public const TYPE_SIDE_QUEST_DEFEAT = 'side-quest-defeat';
    public const TYPE_SIDE_QUEST_VICTORY = 'side-quest-victory';
    public const TYPE_SIDE_QUEST_DRAW = 'side-quest-draw';

    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    public function sideQuestResult()
    {
        return $this->belongsTo(SideQuestResult::class);
    }

    public function newEloquentBuilder($query)
    {
        return new SideQuestEventQueryBuilder($query);
    }

    public function getMappedData()
    {
        $mappedData = [];
        if (array_key_exists('combatMinion', $this->data)) {
            /** @var CombatMinionDataMapper $dataMapper */
            $dataMapper = app(CombatMinionDataMapper::class);
            return $dataMapper->getCombatMinion($this->data['combatMinion']);
        }
        return $mappedData;
    }

    public function getCombatMinion(Collection $combatPositions = null)
    {
        /** @var CombatMinionDataMapper $dataMapper */
        $dataMapper = app(CombatMinionDataMapper::class);
        return $dataMapper->getCombatMinion($this->data['combatMinion'], $combatPositions);
    }

    public function getCombatHero(Collection $combatPositions = null)
    {
        /** @var CombatHeroDataMapper $dataMapper */
        $dataMapper = app(CombatHeroDataMapper::class);
        return $dataMapper->getCombatHero($this->data['combatHero'], $combatPositions);
    }

    public function getDamage()
    {
        return $this->data['damage'];
    }
}
