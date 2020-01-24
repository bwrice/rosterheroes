<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Models\Hero;

class AutoManageHeroAction
{
    /**
     * @var AutoAttachSpiritToHeroAction
     */
    protected $autoAttachSpiritToHeroAction;

    public function __construct(AutoAttachSpiritToHeroAction $autoAttachSpiritToHeroAction)
    {
        $this->autoAttachSpiritToHeroAction = $autoAttachSpiritToHeroAction;
    }

    public function execute(Hero $hero)
    {
        $this->autoAttachSpiritToHeroAction->execute($hero);
    }
}
