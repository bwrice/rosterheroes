<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/14/19
 * Time: 10:32 PM
 */

namespace App\Exceptions;


use App\Domain\Models\League;
use Throwable;

class UnknownLeagueException extends \RuntimeException
{

    /**
     * @var League
     */
    private $league;

    public function __construct(League $league, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "Unknown league with abbreviation: " . $league->abbreviation;
        parent::__construct($message, $code, $previous);
        $this->league = $league;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }
}