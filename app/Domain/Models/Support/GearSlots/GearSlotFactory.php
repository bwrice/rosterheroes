<?php


namespace App\Domain\Models\Support\GearSlots;


class GearSlotFactory
{
    /**
     * @param $gearSlotType
     * @return GearSlot
     */
    public function build(string $gearSlotType): GearSlot
    {
        switch ($gearSlotType) {
            case GearSlot::PRIMARY_ARM:
                return app(PrimaryArm::class);
            case GearSlot::OFF_ARM:
                return app(OffArm::class);
            case GearSlot::HEAD:
                return app(Head::class);
            case GearSlot::TORSO:
                return app(Torso::class);
            case GearSlot::LEGS:
                return app(Legs::class);
            case GearSlot::FEET:
                return app(Feet::class);
            case GearSlot::HANDS:
                return app(Hands::class);
            case GearSlot::WAIST:
                return app(Waist::class);
            case GearSlot::NECK:
                return app(Neck::class);
            case GearSlot::PRIMARY_WRIST:
                return app(PrimaryWrist::class);
            case GearSlot::OFF_WRIST:
                return app(OffWrist::class);
            case GearSlot::RING_ONE:
                return app(RingOne::class);
            case GearSlot::RING_TWO:
                return app(RingTwo::class);
        }
        throw new \InvalidArgumentException("Unknown gear slot: " . $gearSlotType);
    }
}
