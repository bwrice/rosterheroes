<?php


namespace App\Exceptions;


use App\Domain\Interfaces\SpellCaster;
use App\Domain\Models\Spell;
use Throwable;

class SpellCasterException extends \RuntimeException
{
    /**
     * @var SpellCaster
     */
    private $spellCaster;
    /**
     * @var Spell|null
     */
    private $spell;

    public function __construct(SpellCaster $spellCaster, Spell $spell = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->spellCaster = $spellCaster;
        $this->spell = $spell;
    }

    /**
     * @return SpellCaster
     */
    public function getSpellCaster(): SpellCaster
    {
        return $this->spellCaster;
    }

    /**
     * @return Spell|null
     */
    public function getSpell(): ?Spell
    {
        return $this->spell;
    }
}
