<?php

namespace App\Exceptions;

use App\Campaigns\Quests\Quest;
use Exception;
use Throwable;

class QuestRequiredException extends Exception
{
    /**
     * @var Quest
     */
    private $quest;

    public function __construct(Quest $quest, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $quest->name . " is required";
        parent::__construct($message, $code, $previous);
        $this->quest = $quest;
    }

    /**
     * @return Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }
}
