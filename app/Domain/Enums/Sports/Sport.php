<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/12/19
 * Time: 9:26 AM
 */

namespace App\Domain\Enums\Sports;


class Sport
{
    public const FOOTBALL = 'football';
    public const BASKETBALL = 'basketball';
    public const HOCKEY = 'hockey';
    public const BASEBALL = 'baseball';

    /**
     * @var string
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}