<?php


namespace App\Domain\Actions;


use App\Domain\Models\Province;
use App\Domain\Models\Shop;
use Illuminate\Support\Str;

class CreateShop
{
    /**
     * @param string $name
     * @param Province $province
     * @return Shop
     */
    public function execute(string $name, Province $province)
    {
        /** @var Shop $shop */
        $shop = Shop::query()->create([
            'uuid' => Str::uuid(),
            'name' => $name,
            'province_id' => $province->id,
            'tier' => 1
        ]);
        return $shop;
    }
}
