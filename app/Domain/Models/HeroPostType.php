<?php

namespace App\Domain\Models;

use App\Domain\Behaviors\HeroPostTypes\DwarfPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\ElfPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\HeroPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\HumanPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\OrcPostTypeBehavior;
use App\Domain\Collections\HeroRaceCollection;
use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class HeroPostType
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property HeroRaceCollection $heroRaces
 */
class HeroPostType extends Model
{
    public const HUMAN = 'human';
    public const ELF = 'elf';
    public const DWARF = 'dwarf';
    public const ORC = 'orc';

    public const SQUAD_STARTING_HERO_POST_TYPES = [
        [
            'name' => self::HUMAN,
            'count' => 1
        ],
        [
            'name' => self::ELF,
            'count' => 1
        ],
        [
            'name' => self::DWARF,
            'count' => 1
        ],
        [
            'name' => self::ORC,
            'count' => 1
        ]
    ];

    protected $guarded = [];

    public function heroRaces()
    {
        return $this->belongsToMany(HeroRace::class)->withTimestamps();
    }

    /**
     * Returns a collection of HeroPostTypes with keys for how many
     * should be created for a new Squad
     *
     * @return Collection
     */
    public static function squadStarting()
    {
        return collect(self::SQUAD_STARTING_HERO_POST_TYPES);
    }

    /**
     * @return HeroPostTypeBehavior
     */
    public function getBehavior(): HeroPostTypeBehavior
    {
        switch ($this->name) {
            case self::HUMAN:
                return app(HumanPostTypeBehavior::class);
            case self::ELF:
                return app(ElfPostTypeBehavior::class);
            case self::DWARF:
                return app(DwarfPostTypeBehavior::class);
            case self::ORC:
                return app(OrcPostTypeBehavior::class);
        }
        throw new UnknownBehaviorException($this->name, HeroPostTypeBehavior::class);
    }

    /**
     * @param Squad $squad
     * @return int
     */
    public function getRecruitmentCost(Squad $squad)
    {
        $matches = $squad->heroPosts->filter(function (HeroPost $heroPost) {
            return $heroPost->hero_post_type_id === $this->id;
        });

        if ($matches->isEmpty()) {
            return $this->getBehavior()->getRecruitmentCost(0);
        }

        $overInitialOwnershipCount = $matches->count() - $this->squadStartingCount();
        return $this->getBehavior()->getRecruitmentCost($overInitialOwnershipCount);
    }

    public function squadStartingCount()
    {
        $match = self::squadStarting()->first(function ($starting) {
            return $this->name === $starting['name'];
        });

        return $match ? $match['count'] : 0;
    }
}
