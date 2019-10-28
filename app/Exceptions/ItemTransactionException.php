<?php


namespace App\Exceptions;


use App\Domain\Models\Item;
use Throwable;

class ItemTransactionException extends \RuntimeException
{
    public const CODE_NO_BACKUP = 1;
    public const CODE_INVALID_OWNERSHIP = 2;

    /**
     * @var Item
     */
    private $item;

    public function __construct(Item $item, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->item = $item;
    }
}
