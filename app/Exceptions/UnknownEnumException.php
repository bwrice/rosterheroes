<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/12/19
 * Time: 10:06 AM
 */

namespace App\Exceptions;


use Throwable;

class UnknownEnumException extends \RuntimeException
{
    /**
     * @var string
     */
    private $enumClass;
    /**
     * @var string
     */
    private $enumKey;

    public function __construct(string $enumClass, string $enumKey, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->enumClass = $enumClass;
        $this->enumKey = $enumKey;
        $message = $message ?: 'Unknown enum ' . $enumKey . ' for ' . $enumClass;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getEnumClass(): string
    {
        return $this->enumClass;
    }

    /**
     * @return string
     */
    public function getEnumKey(): string
    {
        return $this->enumKey;
    }
}