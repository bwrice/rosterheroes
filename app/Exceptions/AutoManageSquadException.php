<?php


namespace App\Exceptions;


use App\Domain\Models\Squad;
use Throwable;

class AutoManageSquadException extends \Exception
{
    public const CODE_NO_CURRENT_WEEK = 1;
    public const CODE_CURRENT_WEEK_LOCKS_SOON = 2;
    /**
     * @var Squad
     */
    protected $squad;

    public function __construct(Squad $squad, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->squad = $squad;
    }
}
