<?php


namespace App\Domain\Behaviors\Map;


use App\Domain\Models\Territory;
use App\Exceptions\UnknownBehaviorException;

class TerritoryBehaviorFactory
{
    public function getBehavior($territoryName): TerritoryBehavior
    {
        switch ($territoryName) {
            case Territory::GARDENS_OF_REDEMPTION:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a05858',
                        [
                            'pan_x' => 229,
                            'pan_y' => 16,
                            'zoom_x' => 66,
                            'zoom_y' => 50
                        ]
                    )
                );
            case Territory::WOODS_OF_THE_WILD:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#579368',
                        [
                            'pan_x' => 204,
                            'pan_y' => 24,
                            'zoom_x' => 80,
                            'zoom_y' => 60
                        ]
                    )
                );
            case Territory::TWISTING_ISLES_OF_ILLUSIONS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#7950c4',
                        [
                            'pan_x' => 250,
                            'pan_y' => 45,
                            'zoom_x' => 74,
                            'zoom_y' => 56
                        ]
                    )
                );
            case Territory::GRASSLANDS_OF_GIANTS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#af9003',
                        [
                            'pan_x' => 153,
                            'pan_y' => 23,
                            'zoom_x' => 81,
                            'zoom_y' => 62
                        ]
                    )
                );
            case Territory::MENACING_MARCHES:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#1c8a9e',
                        [
                            'pan_x' => 197,
                            'pan_y' => 58,
                            'zoom_x' => 95,
                            'zoom_y' => 72
                        ]
                    )
                );
            case Territory::TROPICS_OF_TREPIDATION:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#869619',
                        [
                            'pan_x' => 248,
                            'pan_y' => 85,
                            'zoom_x' => 89,
                            'zoom_y' => 68
                        ]
                    )
                );
            case Territory::PERILOUS_PLANS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#4e56ce',
                        [
                            'pan_x' => 162,
                            'pan_y' => 66,
                            'zoom_x' => 82,
                            'zoom_y' => 63
                        ]
                    )
                );
            case Territory::TREACHEROUS_FOREST:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#b73583',
                        [
                            'pan_x' => 108,
                            'pan_y' => 54,
                            'zoom_x' => 95,
                            'zoom_y' => 72
                        ]
                    )
                );
            case Territory::SAVAGE_SWAMPS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a56517',
                        [
                            'pan_x' => 235,
                            'pan_y' => 110,
                            'zoom_x' => 75,
                            'zoom_y' => 57
                        ]
                    )
                );
            case Territory::DESERT_OF_DESPAIR:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#99844a',
                        [
                            'pan_x' => 190,
                            'pan_y' => 105,
                            'zoom_x' => 75,
                            'zoom_y' => 57
                        ]
                    )
                );
            case Territory::HUMBLING_HILLS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#0ab5a1',
                        [
                            'pan_x' => 76,
                            'pan_y' => 29,
                            'zoom_x' => 114,
                            'zoom_y' => 87
                        ]
                    )
                );
            case Territory::GULF_OF_SERPENTS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5b9621',
                        [
                            'pan_x' => 150,
                            'pan_y' => 104,
                            'zoom_x' => 86,
                            'zoom_y' => 66
                        ]
                    )
                );
            case Territory::CARNIVOROUS_CANYONS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#8e2964',
                        [
                            'pan_x' => 178,
                            'pan_y' => 137,
                            'zoom_x' => 100,
                            'zoom_y' => 76
                        ]
                    )
                );
            case Territory::INFERNAL_ISLANDS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#c95a00',
                        [
                            'pan_x' => 118,
                            'pan_y' => 106,
                            'zoom_x' => 72,
                            'zoom_y' => 55
                        ]
                    )
                );
            case Territory::JADE_JUNGLE:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#377a23',
                        [
                            'pan_x' => 74,
                            'pan_y' => 91,
                            'zoom_x' => 118,
                            'zoom_y' => 90
                        ]
                    )
                );
            case Territory::SHORES_OF_THE_SHADOWS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#9e775a',
                        [
                            'pan_x' => 60,
                            'pan_y' => 12,
                            'zoom_x' => 102,
                            'zoom_y' => 78
                        ]
                    )
                );
            case Territory::VALLEY_OF_VANISHINGS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#8339ba',
                        [
                            'pan_x' => 48,
                            'pan_y' => 57,
                            'zoom_x' => 104,
                            'zoom_y' => 79
                        ]
                    )
                );
            case Territory::PRIMAL_PASSAGE:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5844c9',
                        [
                            'pan_x' => 148,
                            'pan_y' => 153,
                            'zoom_x' => 64,
                            'zoom_y' => 49
                        ]
                    )
                );
            case Territory::ARCTIC_ARCHIPELAGO:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#586b6d',
                        [
                            'pan_x' => 24,
                            'pan_y' => -28,
                            'zoom_x' => 146,
                            'zoom_y' => 111
                        ]
                    )
                );
            case Territory::BADLANDS_OF_THE_LEVIATHAN:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#8c6d4f',
                        [
                            'pan_x' => 60,
                            'pan_y' => 113,
                            'zoom_x' => 84,
                            'zoom_y' => 64
                        ]
                    )
                );
            case Territory::PEAKS_OF_PANDORA:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a3277c',
                        [
                            'pan_x' => 26,
                            'pan_y' => 56,
                            'zoom_x' => 105,
                            'zoom_y' => 80
                        ]
                    )
                );
            case Territory::CRYPTIC_COAST:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#ba3f3f',
                        [
                            'pan_x' => 3,
                            'pan_y' => 36,
                            'zoom_x' => 100,
                            'zoom_y' => 75
                        ]
                    )
                );
            case Territory::WETLANDS_OF_WANDERING_TORMENT:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#196e96',
                        [
                            'pan_x' => 20,
                            'pan_y' => 82,
                            'zoom_x' => 95,
                            'zoom_y' => 72
                        ]
                    )
                );
            case Territory::BLOODSTONE_BEACHES:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#af9f08',
                        [
                            'pan_x' => 50,
                            'pan_y' => 132,
                            'zoom_x' => 110,
                            'zoom_y' => 84
                        ]
                    )
                );
            case Territory::TURBULENT_TUNDRA:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5ba373',
                        [
                            'pan_x' => 9,
                            'pan_y' => 16,
                            'zoom_x' => 68,
                            'zoom_y' => 52
                        ]
                    )
                );
            case Territory::COVES_OF_CORRUPTION:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#c62e1d',
                        [
                            'pan_x' => 100,
                            'pan_y' => 176,
                            'zoom_x' => 70,
                            'zoom_y' => 53
                        ]
                    )
                );
            case Territory::MOUNTAINS_OF_MISERY:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#6c6491',
                        [
                            'pan_x' => -40,
                            'pan_y' => 44,
                            'zoom_x' => 130,
                            'zoom_y' => 99
                        ]
                    )
                );
            case Territory::ENCLAVE_OF_ENIGMAS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#598e47',
                        [
                            'pan_x' => 0,
                            'pan_y' => 118,
                            'zoom_x' => 86,
                            'zoom_y' => 66
                        ]
                    )
                );
            case Territory::SCREAMING_HIGHLANDS_OF_NIGHTMARES:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#74367f',
                        [
                            'pan_x' => 29,
                            'pan_y' => 165,
                            'zoom_x' => 90,
                            'zoom_y' => 69
                        ]
                    )
                );
            case Territory::CLOUD_PIERCING_CLIFFS:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#a05709',
                        [
                            'pan_x' => -52,
                            'pan_y' => 70,
                            'zoom_x' => 136,
                            'zoom_y' => 104
                        ]
                    )
                );
            case Territory::OUTER_RIM_OF_THE_DEMONIC:
                return new TerritoryBehavior(
                    new RealmBehavior(
                        '#5277af',
                        [
                            'pan_x' => -7,
                            'pan_y' => 156,
                            'zoom_x' => 110,
                            'zoom_y' => 84
                        ]
                    )
                );
        }

        throw new UnknownBehaviorException($territoryName, TerritoryBehavior::class);
    }
}
