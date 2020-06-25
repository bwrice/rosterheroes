<?php


namespace App\Exceptions;


use Throwable;

class CreateItemBlueprintException extends \Exception
{
    public const CODE_INVALID_ITEM_TYPES = 1;
    public const CODE_INVALID_MATERIALS = 2;
    public const CODE_INVALID_ITEM_CLASSES = 3;
    public const CODE_INVALID_ITEM_BASES = 4;
}
