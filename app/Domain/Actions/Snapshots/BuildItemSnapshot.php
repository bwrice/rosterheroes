<?php


namespace App\Domain\Actions\Snapshots;

use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\ItemSnapshot;
use App\Domain\Models\HeroSnapshot;
use Illuminate\Support\Str;

class BuildItemSnapshot extends BuildWeeklySnapshot
{
    public const EXCEPTION_CODE_HERO_MISMATCH = 2;

    /**
     * @var BuildAttackSnapshot
     */
    protected $buildAttackSnapshot;

    public function __construct(BuildAttackSnapshot $buildAttackSnapshot)
    {
        $this->buildAttackSnapshot = $buildAttackSnapshot;
    }

    public function handle(Item $item, HeroSnapshot $heroSnapshot)
    {
        if ($item->has_items_id !== $heroSnapshot->hero_id || $item->has_items_type !== Hero::RELATION_MORPH_MAP_KEY) {
            throw new \Exception("Item is not equipped by hero of hero snapshot", self::EXCEPTION_CODE_HERO_MISMATCH);
        }

        /** @var ItemSnapshot $itemSnapshot */
        $itemSnapshot = $heroSnapshot->itemSnapshots()->create([
            'uuid' => Str::uuid(),
            'item_id' => $item->id,
            'item_type_id' => $item->item_type_id,
            'material_id' => $item->material_id,
            'name' => $item->name,
            'protection' => $item->getProtection(),
            'weight' => $item->weight(),
            'value' => $item->getValue(),
            'block_chance' => $item->getBlockChance()
        ]);
        $fantasyPower = $heroSnapshot->fantasy_power;
        $item->getAttacks()->each(function (Attack $attack) use ($itemSnapshot, $fantasyPower) {
            $this->buildAttackSnapshot->execute($attack, $itemSnapshot, $fantasyPower);
        });

        $itemSnapshot->enchantments()->saveMany($item->enchantments);

        return $itemSnapshot;
    }
}
