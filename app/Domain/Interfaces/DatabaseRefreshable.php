<?php


namespace App\Domain\Interfaces;


interface DatabaseRefreshable
{
    /** @return static */
    public function fresh();
}
