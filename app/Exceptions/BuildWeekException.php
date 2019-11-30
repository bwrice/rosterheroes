<?php


namespace App\Exceptions;


class BuildWeekException extends \RuntimeException
{
    public const CODE_INVALID_CURRENT_WEEK = 1;
}
