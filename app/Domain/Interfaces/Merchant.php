<?php


namespace App\Domain\Interfaces;


interface Merchant
{
    public function getSlug(): string;

    public function getMerchantType(): string;
}
