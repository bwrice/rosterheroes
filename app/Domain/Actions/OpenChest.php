<?php


namespace App\Domain\Actions;


use App\Chest;
use App\Domain\Collections\HasItemsCollection;
use App\Domain\DataTransferObjects\OpenedChestResult;
use App\Domain\Models\Item;
use Illuminate\Support\Facades\DB;

class OpenChest
{
    /**
     * @var AddItemToHasItems
     */
    protected $addItemToHasItems;

    public function __construct(AddItemToHasItems $addItemToHasItems)
    {
        $this->addItemToHasItems = $addItemToHasItems;
    }

    /**
     * @param Chest $chest
     * @return OpenedChestResult
     * @throws \Exception
     */
    public function execute(Chest $chest)
    {
        if ($chest->opened_at) {
            throw new \Exception("Chest already opened");
        }

        return DB::transaction(function () use ($chest) {

            $chest->opened_at = now();
            $chest->save();

            $squad = $chest->squad;

            $gold = $chest->gold;
            $squad->getAggregate()->increaseGold($gold)->persist();

            $hasItemsCollection = new HasItemsCollection();

            $chest->items->each(function (Item $item) use ($squad, &$hasItemsCollection) {
                $hasItemsCollection = $this->addItemToHasItems->execute($item, $squad, $hasItemsCollection, false);
            });

            return new OpenedChestResult($gold, $hasItemsCollection);
        });
    }
}
