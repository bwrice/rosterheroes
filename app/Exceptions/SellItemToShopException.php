<?php


namespace App\Exceptions;


use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use Throwable;

class SellItemToShopException extends \Exception
{

    public const CODE_INVALID_ITEM_OWNERSHIP = 1;
    public const CODE_INVALID_PROVINCE = 2;

    /**
     * @var Item
     */
    protected $item;
    /**
     * @var Squad
     */
    protected $squad;
    /**
     * @var Shop
     */
    protected $shop;

    public function __construct(
        Item $item,
        Squad $squad,
        Shop $shop,
        $message = "",
        $code = 0,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->item = $item;
        $this->squad = $squad;
        $this->shop = $shop;
    }
}
