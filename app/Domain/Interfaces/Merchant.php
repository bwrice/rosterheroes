<?php


namespace App\Domain\Interfaces;


interface Merchant
{
    public function getName(): string;

    public function getSlug(): string;

    public function getMerchantType(): string;
}
