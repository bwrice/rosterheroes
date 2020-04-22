<?php


namespace App\Domain\Actions;


use App\Chest;
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

    public function execute(Chest $chest)
    {
        if ($chest->opened_at) {
            throw new \Exception("Chest already opened");
        }

        DB::transaction(function () use ($chest) {

            $chest->opened_at = now();
            $chest->save();

            $squad = $chest->squad;

            $squad->getAggregate()->increaseGold($chest->gold)->persist();

            $chest->items->each(function (Item $item) use ($squad) {
                $this->addItemToHasItems->execute($item, $squad, null, false);
            });
        });
    }
}
