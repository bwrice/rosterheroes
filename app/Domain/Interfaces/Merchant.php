<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\Province;

interface Merchant
{
    public function getName(): string;

    public function getSlug(): string;

    public function getMerchantType(): string;

    public function getProvince(): Province;

    public function getProvinceID(): int;
}
