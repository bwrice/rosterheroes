<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\HeroPostTypes\DwarfPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\ElfPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\HeroPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\HumanPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\OrcPostTypeBehavior;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class HeroPostTypeService
 * @package App\Services\Models\Reference
 *
 * @method  HeroPostTypeBehavior getBehavior($identifier)
 */
class HeroPostTypeService extends ReferenceService
{
    public const SQUAD_STARTING_HERO_POST_TYPES = [
        [
            'name' => HeroPostType::HUMAN,
            'count' => 1
        ],
        [
            'name' => HeroPostType::ELF,
            'count' => 1
        ],
        [
            'name' => HeroPostType::DWARF,
            'count' => 1
        ],
        [
            'name' => HeroPostType::ORC,
            'count' => 1
        ]
    ];

    public function __construct()
    {
        $this->behaviors[HeroPostType::ORC] = app(OrcPostTypeBehavior::class);
        $this->behaviors[HeroPostType::DWARF] = app(DwarfPostTypeBehavior::class);
        $this->behaviors[HeroPostType::ELF] = app(ElfPostTypeBehavior::class);
        $this->behaviors[HeroPostType::HUMAN] = app(HumanPostTypeBehavior::class);

    }

    protected function all(): Collection
    {
        return HeroPostType::all();
    }

    public function cheapestForSquad(Squad $squad)
    {
        return $this->all()->groupBy(function (HeroPostType $heroPostType) use ($squad) {
            $ownerShipCount = $squad->loadMissing('heroPosts')
                ->heroPosts
                ->filter(function (HeroPost $heroPost) use ($heroPostType) {
                    return $heroPost->hero_post_type_id === $heroPostType->id;
                })->count();
            $overInitialCount = $ownerShipCount - $this->squadStartingCount($heroPostType->name);
            return $this->getBehavior($heroPostType->name)->getRecruitmentCost($overInitialCount);
        })->sortKeys()->first();
    }

    public function squadStartingCount(string $heroPostTypeName)
    {
        $match = $this->squadStarting()->first(function ($starting) use ($heroPostTypeName) {
            return $heroPostTypeName === $starting['name'];
        });

        return $match ? $match['count'] : 0;
    }

    /**
     * Returns a collection of HeroPostTypes with keys for how many
     * should be created for a new Squad
     *
     * @return Collection
     */
    public function squadStarting()
    {
        return collect(self::SQUAD_STARTING_HERO_POST_TYPES);
    }
}
