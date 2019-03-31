<?php

namespace App\Exceptions;

use App\Domain\Models\Quest;
use Exception;
use Throwable;

class QuestExistsException extends Exception
{
    /**
     * @var Quest
     */
    private $quest;

    public function __construct(Quest $quest, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->quest = $quest;
        $message = $message ?: $quest->name . ' already exists';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }
}
