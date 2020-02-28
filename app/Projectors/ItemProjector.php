<?php

namespace App\Projectors;

use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;
use App\StorableEvents\AttackAttachedToItem;
use App\StorableEvents\HeroKillsMinionSideQuestEvent;
use App\StorableEvents\ItemCreated;
use App\Domain\Models\Item;
use App\StorableEvents\EnchantmentAttachedToItem;
use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class ItemProjector implements Projector
{
    use ProjectsEvents;

    public function onItemCreationRequested(ItemCreated $event, string $aggregateUuid)
    {
        Item::query()->create([
            'uuid' => $aggregateUuid,
            'item_class_id' => $event->itemClassID,
            'item_type_id' => $event->itemTypeID,
            'material_id' => $event->materialTypeID,
            'item_blueprint_id' => $event->itemBlueprintID,
            'name' => $event->name
        ]);
    }

    public function onEnchantmentAttachedToItem(EnchantmentAttachedToItem $event, string $aggregateUuid)
    {
        $item = Item::findUuid($aggregateUuid);
        $item->enchantments()->attach($event->enchantmentID);
    }

    public function onAttackAttachedToItem(AttackAttachedToItem $event, string $aggregateUuid)
    {
        $item = Item::findUuid($aggregateUuid);
        $item->attacks()->attach($event->attackID);
    }

    public function onHeroDamagesMinionSideQuestEvent(HeroDamagesMinionSideQuestEvent $event)
    {
        $heroCombatAttack = $this->getHeroCombatAttackDataMapper()
            ->getHeroCombatAttack($event->getHeroCombatAttackData());

        $item = Item::findUuidOrFail($heroCombatAttack->getItemUuid());
        $item->damage_dealt += $event->getDamage();
        $item->save();
    }

    public function onHeroKillsMinionSideQuestEvent(HeroKillsMinionSideQuestEvent $event)
    {
        $heroCombatAttack = $this->getHeroCombatAttackDataMapper()
            ->getHeroCombatAttack($event->getHeroCombatAttackData());

        $item = Item::findUuidOrFail($heroCombatAttack->getItemUuid());
        $item->damage_dealt += $event->getDamage();
        $item->minion_kills++;
        $item->save();
    }

    /**
     * @return HeroCombatAttackDataMapper
     */
    protected function getHeroCombatAttackDataMapper()
    {
        return app(HeroCombatAttackDataMapper::class);
    }
}
