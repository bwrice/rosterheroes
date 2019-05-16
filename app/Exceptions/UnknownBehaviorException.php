<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/13/19
 * Time: 7:22 PM
 */

namespace App\Exceptions;


use Throwable;

class UnknownBehaviorException extends \RuntimeException
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $behaviorClass;

    public function __construct(string $key, string $behaviorClass, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->key = $key;
        $this->behaviorClass = $behaviorClass;
        $message = $message ?: "Unknown key: " . $key . " for behavior class: " . $behaviorClass;
        parent::__construct($message, $code, $previous);
    }
}