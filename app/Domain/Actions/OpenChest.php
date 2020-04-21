<?php


namespace App\Domain\Actions;


use App\Chest;
use Illuminate\Support\Facades\DB;

class OpenChest
{
    public function execute(Chest $chest)
    {
        if ($chest->opened_at) {
            throw new \Exception("Chest already opened");
        }

        DB::transaction(function () use ($chest) {

            $chest->opened_at = now();
            $chest->save();

            $chest->squad->getAggregate()->increaseGold($chest->gold)->persist();
        });
    }
}
