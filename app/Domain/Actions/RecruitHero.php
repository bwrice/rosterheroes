<?php


namespace App\Domain\Actions;


use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use App\Exceptions\RecruitHeroException;

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

    /**
     * @param Squad $squad
     * @param RecruitmentCamp $recruitmentCamp
     * @param HeroPostType $heroPostType
     * @param HeroRace $heroRace
     * @param HeroClass $heroClass
     * @throws RecruitHeroException
     */
    public function execute(Squad $squad, RecruitmentCamp $recruitmentCamp, HeroPostType $heroPostType, HeroRace $heroRace, HeroClass $heroClass)
    {
        $this->squad = $squad;
        $this->recruitmentCamp = $recruitmentCamp;
        $this->heroPostType = $heroPostType;
        $this->heroRace = $heroRace;
        $this->heroClass = $heroClass;
        $this->validateLocation();
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
