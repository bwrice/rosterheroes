<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class SquadCreated implements ShouldBeStored
{
    /** @var int */
    public $userID;

    /** @var string */
    public $name;

    /** @var int */
    public $squadRankID;

    /** @var int */
    public $mobileStorageRankID;

    /** @var int */
    public $provinceID;

    /**
     * SquadCreated constructor.
     * @param int $userID
     * @param string $name
     * @param int $squadRankID
     * @param int $mobileStorageRankID
     * @param int $provinceID
     */
    public function __construct(int $userID,
                                string $name,
                                int $squadRankID,
                                int $mobileStorageRankID,
                                int $provinceID)
    {
        $this->userID = $userID;
        $this->name = $name;
        $this->squadRankID = $squadRankID;
        $this->mobileStorageRankID = $mobileStorageRankID;
        $this->provinceID = $provinceID;
    }

}
