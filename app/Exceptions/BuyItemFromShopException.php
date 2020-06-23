<?php


namespace App\Exceptions;


use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Throwable;

class BuyItemFromShopException extends \Exception
{
    public const CODE_INVALID_OWNERSHIP = 1;
    public const CODE_INVALID_PROVINCE = 2;
    public const CODE_NOT_ENOUGH_GOLD = 3;


    /**
     * @var Item
     */
    protected $item;
    /**
     * @var Shop
     */
    protected $shop;
    /**
     * @var Squad
     */
    protected $squad;

    public function __construct(Item $item, Shop $shop, Squad $squad, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->item = $item;
        $this->shop = $shop;
        $this->squad = $squad;
    }
}
