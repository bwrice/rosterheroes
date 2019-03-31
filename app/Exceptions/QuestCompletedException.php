<?php

namespace App\Exceptions;

use App\Domain\Models\Quest;
use Carbon\Carbon;
use Exception;
use Throwable;

class QuestCompletedException extends Exception
{
    /**
     * @var \App\Domain\Models\Quest
     */
    private $quest;

    public function __construct(Quest $quest, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $quest->name . " has already been completed";
        parent::__construct($message, $code, $previous);
        $this->quest = $quest;
    }

    /**
     * @return \App\Domain\Models\Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }
}
