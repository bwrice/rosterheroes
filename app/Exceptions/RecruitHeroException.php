<?php


namespace App\Exceptions;


use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\HeroRace;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Squad;
use Throwable;

class RecruitHeroException extends \Exception
{
    public const CODE_INVALID_SQUAD_LOCATION = 1;
    /**
     * @var Squad
     */
    protected $squad;
    /**
     * @var RecruitmentCamp
     */
    protected $recruitmentCamp;
    /**
     * @var HeroPostType
     */
    protected $heroPostType;
    /**
     * @var HeroRace
     */
    protected $heroRace;
    /**
     * @var HeroClass
     */
    protected $heroClass;

    public function __construct(
        Squad $squad,
        RecruitmentCamp $recruitmentCamp,
        HeroPostType $heroPostType,
        HeroRace $heroRace,
        HeroClass $heroClass,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->squad = $squad;
        $this->recruitmentCamp = $recruitmentCamp;
        $this->heroPostType = $heroPostType;
        $this->heroRace = $heroRace;
        $this->heroClass = $heroClass;
    }
}
