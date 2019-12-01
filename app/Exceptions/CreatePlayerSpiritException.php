<?php


namespace App\Exceptions;


class CreatePlayerSpiritException extends \RuntimeException
{
    public const CODE_INVALID_GAME_TIME = 1;
    public const CODE_TEAM_NOT_PART_OF_GAME = 2;
    public const CODE_INVALID_PLAYER_POSITIONS = 3;
}
