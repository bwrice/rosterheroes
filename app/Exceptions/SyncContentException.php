<?php


namespace App\Exceptions;


class SyncContentException extends \Exception
{
    public const CODE_ATTACKS_NOT_SYNCED = 1;
    public const CODE_ITEM_TYPES_NOT_SYNCED = 2;
}
