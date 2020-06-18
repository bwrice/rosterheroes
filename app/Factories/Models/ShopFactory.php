<?php


namespace App\Factories\Models;


use App\Domain\Models\Province;
use App\Domain\Models\Shop;
use Illuminate\Support\Str;

class ShopFactory
{
    protected $provinceID;

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Shop
     */
    public function create(array $extra = []): Shop
    {
        /** @var Shop $shop */
        $shop = Shop::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test Shop ' . rand(1, 999999),
            'province_id' => $this->getProvinceID(),
            'tier' => 1
        ], $extra));


        return $shop;
    }

    public function withProvinceID(int $provinceID)
    {
        $clone = clone $this;
        $clone->provinceID = $provinceID;
        return $clone;
    }

    protected function getProvinceID()
    {
        return $this->provinceID ?: Province::query()->inRandomOrder()->first()->id;
    }
}
