<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use Illuminate\Support\Collection;

class UnEquipItemFromHeroAction
{
    /**
     * @var MoveItemToBackupAction
     */
    private $moveItemToBackupAction;

    public function __construct(MoveItemToBackupAction $moveItemToBackupAction)
    {
        $this->moveItemToBackupAction = $moveItemToBackupAction;
    }

    /**
     * @param Item $item
     * @param Hero $hero
     * @param Collection|null $hasSlots
     * @return Collection
     */
    public function execute(Item $item, Hero $hero, Collection $hasSlots = null): Collection
    {
        if ($item->hasItems->getMorphType() !== Hero::RELATION_MORPH_MAP_KEY && $item->hasItems->getMorphID() !== $hero->id) {
            throw new ItemTransactionException($item, "Item does not belong to hero", ItemTransactionException::CODE_INVALID_OWNERSHIP);
        }
        return $this->moveItemToBackupAction->execute($item, $hero, $hasSlots);
    }
}
