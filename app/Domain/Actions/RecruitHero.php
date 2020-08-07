<?php


namespace App\Domain\Actions;


use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Exceptions\RecruitHeroException;
use App\Facades\CurrentWeek;

class RecruitHero
{
    /** @var Squad */
    protected $squad;

    /** @var RecruitmentCamp */
    protected $recruitmentCamp;

    /** @var HeroPostType */
    protected $heroPostType;

    /** @var HeroRace */
    protected $heroRace;

    /** @var HeroClass */
    protected $heroClass;

    /** @var string $heroName */
    protected $heroName;
    /**
     * @var AddNewHeroToSquadAction
     */
    private $addNewHeroToSquadAction;

    public function __construct(AddNewHeroToSquadAction $addNewHeroToSquadAction)
    {
        $this->addNewHeroToSquadAction = $addNewHeroToSquadAction;
    }

    /**
     * @param Squad $squad
     * @param RecruitmentCamp $recruitmentCamp
     * @param HeroPostType $heroPostType
     * @param HeroRace $heroRace
     * @param HeroClass $heroClass
     * @param string $heroName
     * @return \App\Domain\Models\Hero
     * @throws RecruitHeroException
     * @throws \App\Exceptions\HeroPostNotFoundException
     * @throws \App\Exceptions\InvalidHeroClassException
     */
    public function execute(
        Squad $squad,
        RecruitmentCamp $recruitmentCamp,
        HeroPostType $heroPostType,
        HeroRace $heroRace,
        HeroClass $heroClass,
        string $heroName)
    {
        $this->squad = $squad;
        $this->recruitmentCamp = $recruitmentCamp;
        $this->heroPostType = $heroPostType;
        $this->heroRace = $heroRace;
        $this->heroClass = $heroClass;
        $this->heroName = $heroName;

        $this->validateWeek();
        $this->validateLocation();
        $this->validateHeroRace();
        $this->validateGold();

        $squad->heroPosts()->create([
            'hero_post_type_id' => $this->heroPostType->id
        ]);

        $bonusSpiritsEssence = $this->heroPostType->getRecruitmentBonusSpiritEssence($this->squad);
        $squad->spirit_essence += $bonusSpiritsEssence;
        $squad->save();

        $goldCost = $this->heroPostType->getRecruitmentCost($this->squad);
        $squad->gold -= $goldCost;
        $squad->save();

        return $this->addNewHeroToSquadAction->execute($this->squad->fresh(), $this->heroName, $this->heroClass, $this->heroRace);
    }

    /**
     * @throws RecruitHeroException
     */
    protected function validateWeek()
    {
        if (CurrentWeek::adventuringLocked()) {
            $this->throwException("Current week is locked", RecruitHeroException::CODE_WEEK_LOCKED);
        }
    }

    /**
     * @throws RecruitHeroException
     */
    protected function validateLocation()
    {
        if ($this->squad->province_id !== $this->recruitmentCamp->province_id) {
            $message = $this->squad->name . " is not in the same province as " . $this->recruitmentCamp->name;
            $this->throwException($message, RecruitHeroException::CODE_INVALID_SQUAD_LOCATION);
        }
    }

    /**
     * @throws RecruitHeroException
     */
    protected function validateHeroRace()
    {
        if (! in_array($this->heroRace->id, $this->heroPostType->heroRaces->pluck('id')->toArray())) {
            $this->throwException($this->heroRace->name . " is not valid", RecruitHeroException::CODE_INVALID_HERO_RACE);
        }
    }

    /**
     * @throws RecruitHeroException
     */
    protected function validateGold()
    {
        $recruitmentCost = $this->heroPostType->getRecruitmentCost($this->squad);
        if ($this->squad->gold < $recruitmentCost) {
            $message = $recruitmentCost . " gold required, but only " . $this->squad->gold . " available";
            $this->throwException($message, RecruitHeroException::CODE_NOT_ENOUGH_GOLD);
        }
    }

    /**
     * @param string $message
     * @param int $code
     * @throws RecruitHeroException
     */
    protected function throwException(string $message, int $code)
    {
        throw new RecruitHeroException(
            $this->squad,
            $this->recruitmentCamp,
            $this->heroPostType,
            $this->heroRace,
            $this->heroClass,
            $message,
            $code
        );
    }
}
