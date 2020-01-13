<?php


namespace App\Exceptions;


use App\Domain\Models\Squad;
use Throwable;

class BuildSquadSnapshotException extends \RuntimeException
{
    public const CODE_WEEK_NOT_FINALIZED = 1;

    /**
     * @var Squad
     */
    private $squad;

    public function __construct(Squad $squad, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->squad = $squad;
    }

    /**
     * @return Squad
     */
    public function getSquad(): Squad
    {
        return $this->squad;
    }
}
