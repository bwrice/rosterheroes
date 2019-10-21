<?php


namespace App\Exceptions;


use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use Throwable;

class AddSpellException extends \RuntimeException
{
    public const CODE_ALREADY_OWNS = 1;

    /**
     * @var Squad
     */
    private $squad;
    /**
     * @var Spell
     */
    private $spell;

    public function __construct(Squad $squad, Spell $spell, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->squad = $squad;
        $this->spell = $spell;
    }

    /**
     * @return Squad
     */
    public function getSquad(): Squad
    {
        return $this->squad;
    }

    /**
     * @return Spell
     */
    public function getSpell(): Spell
    {
        return $this->spell;
    }
}
