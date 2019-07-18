<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/17/19
 * Time: 10:10 PM
 */

namespace App\Exceptions;


use App\Domain\Models\ItemBlueprint;
use Throwable;

class InvalidItemBlueprintException extends \RuntimeException
{
    /**
     * @var ItemBlueprint
     */
    private $itemBlueprint;

    public function __construct(ItemBlueprint $itemBlueprint, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->itemBlueprint = $itemBlueprint;
        $message = $message ?: 'ItemBlueprint with id: ' . $itemBlueprint->id . ' is invalid';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ItemBlueprint
     */
    public function getItemBlueprint(): ItemBlueprint
    {
        return $this->itemBlueprint;
    }
}