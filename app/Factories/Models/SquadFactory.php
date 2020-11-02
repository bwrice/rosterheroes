<?php


namespace App\Factories\Models;


use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SquadFactory
{
    /** @var Collection|null */
    protected $heroFactories;

    protected $userID;

    protected $provinceID;

    protected $squadRankID;

    protected $mobileStorageRankID;

    protected $withStartingHeroes = false;

    protected ?int $gold = null;

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Squad
     */
    public function create(array $extra = []): Squad
    {
        /** @var Squad $squad */
        $squad = Squad::query()->create(array_merge(
            [
                'user_id' => $this->userID ?: factory(User::class)->create()->id,
                'uuid' => Str::uuid(),
                'name' => 'TestSquad_' . rand(1,999999999),
                'province_id' => $this->provinceID ?: Province::getStarting()->id,
                'squad_rank_id' => $this->squadRankID ?: SquadRank::getStarting()->id,
                'mobile_storage_rank_id' => $this->mobileStorageRankID ?: MobileStorageRank::getStarting()->id,
                'spirit_essence' => Squad::STARTING_ESSENCE,
                'gold' => $this->gold ?: Squad::STARTING_GOLD,
                'experience' => Squad::STARTING_EXPERIENCE,
                'favor' => Squad::STARTING_FAVOR,
            ],
            $extra
        ));
        if ($this->heroFactories) {
            $this->heroFactories->each(function (HeroFactory $heroFactory) use ($squad) {
                $heroFactory->forSquad($squad)->create();
            });
        } elseif ($this->withStartingHeroes) {

            $heroClasses = HeroClass::requiredStarting()->get();
            $heroPostTypes = HeroPostType::query()->with('heroRaces')->get();

            HeroPostType::squadStarting()->each(function ($squadStarting) use ($heroPostTypes, $heroClasses, $squad) {
                /** @var HeroPostType $postType */
                $postType = $heroPostTypes->first(function (HeroPostType $heroPostType) use ($squadStarting) {
                    return $heroPostType->name === $squadStarting['name'];
                });

                // Create valid hero post
                HeroPostFactory::new()->forSquad($squad->id)->forHeroPostType($postType->id)->create();

                // Creat hero
                /** @var HeroRace $heroRace */
                $heroRace = $postType->heroRaces()->first();
                $heroFactory = HeroFactory::new()->forSquad($squad)->heroRace($heroRace->name);
                $heroClass = $heroClasses->shift();
                if ($heroClass) {
                    $heroFactory->heroClass($heroClass->name);
                }
                $heroFactory->create();
            });
        }

        return $squad;
    }

    public function withGold(int $gold)
    {
        $clone = clone $this;
        $clone->gold = $gold;
        return $clone;
    }

    public function atProvince(int $provinceID)
    {
        $clone = clone $this;
        $clone->provinceID = $provinceID;
        return $clone;
    }

    public function withHeroes(Collection $heroFactories)
    {
        $clone = clone $this;
        $clone->heroFactories = $heroFactories;
        return $clone;
    }

    public function withStartingHeroes()
    {
        $clone = clone $this;
        $clone->withStartingHeroes = true;
        return $clone;
    }
}
