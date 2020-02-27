<?php


namespace App\StorableEvents;


abstract class StorableSideQuestEvent
{

    /**
     * @var int
     */
    public $sideQuestResultID;
    /**
     * @var int
     */
    public $moment;
    /**
     * @var array
     */
    public $data;

    public function __construct(
        int $sideQuestResultID,
        int $moment,
        array $data)
    {
        $this->sideQuestResultID = $sideQuestResultID;
        $this->moment = $moment;
        $this->data = $data;
    }
}
