<?php


namespace Tests\Traits;


use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;
use PHPUnit\Framework\Assert;

/**
 * Trait TestsItemsTransactions
 * @package Tests\Traits
 *
 * @mixin Assert
 */
trait TestsItemsTransactions
{
    public function assertItemTransactionMatches(Item $item, HasItems $to, HasItems $from)
    {
        $this->assertEquals([
            'to' => $to->getTransactionIdentification(),
            'from' => $from->getTransactionIdentification()
        ], $item->getTransaction());
    }
}
