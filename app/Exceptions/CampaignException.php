<?php


namespace App\Exceptions;


use App\Domain\Models\Squad;

class CampaignException extends \RuntimeException
{
    public const CODE_WEEK_LOCKED = 1;

    /** @var Squad|null */
    protected $squad;

    /**
     * @param Squad|null $squad
     * @return CampaignException
     */
    public function setSquad(?Squad $squad): CampaignException
    {
        $this->squad = $squad;
        return $this;
    }

    /**
     * @return Squad|null
     */
    public function getSquad(): ?Squad
    {
        return $this->squad;
    }


}
