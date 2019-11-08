<?php


namespace App\Domain\Behaviors\Realm;


use App\Domain\Behaviors\Realm\Territories\ArcticArchipelagoBehavior;
use App\Domain\Behaviors\Realm\Territories\BadlandsOfTheLeviathanBehavior;
use App\Domain\Behaviors\Realm\Territories\BloodstoneBeachesBehavior;
use App\Domain\Behaviors\Realm\Territories\CarnivorousCanyonsBehavior;
use App\Domain\Behaviors\Realm\Territories\CloudPiercingCliffsBehavior;
use App\Domain\Behaviors\Realm\Territories\CovesOfCorruptionBehavior;
use App\Domain\Behaviors\Realm\Territories\CrypticCoastBehavior;
use App\Domain\Behaviors\Realm\Territories\DesertOfDespairBehavior;
use App\Domain\Behaviors\Realm\Territories\EnclaveOfEnigmasBehavior;
use App\Domain\Behaviors\Realm\Territories\GardensOfRedemptionBehavior;
use App\Domain\Behaviors\Realm\Territories\GrasslandsOfGiantsBehavior;
use App\Domain\Behaviors\Realm\Territories\GulfOfSerpentsBehavior;
use App\Domain\Behaviors\Realm\Territories\HumblingHillsBehavior;
use App\Domain\Behaviors\Realm\Territories\InfernalIslandsBehavior;
use App\Domain\Behaviors\Realm\Territories\JadeJungleBehavior;
use App\Domain\Behaviors\Realm\Territories\MenacingMarchesBehavior;
use App\Domain\Behaviors\Realm\Territories\MountainsOfMiseryBehavior;
use App\Domain\Behaviors\Realm\Territories\OuterRimeOfTheDemonicBehavior;
use App\Domain\Behaviors\Realm\Territories\PeaksOfPandoraBehavior;
use App\Domain\Behaviors\Realm\Territories\PerilousPlainsBehavior;
use App\Domain\Behaviors\Realm\Territories\PrimalPassageBehavior;
use App\Domain\Behaviors\Realm\Territories\SavageSwampsBehavior;
use App\Domain\Behaviors\Realm\Territories\ScreamingHighlandsOfNightmaresBehavior;
use App\Domain\Behaviors\Realm\Territories\ShoresOfTheShadowsBehavior;
use App\Domain\Behaviors\Realm\Territories\TerritoryBehavior;
use App\Domain\Behaviors\Realm\Territories\TreacherousForestBehavior;
use App\Domain\Behaviors\Realm\Territories\TropicsOfTrepidationBehavior;
use App\Domain\Behaviors\Realm\Territories\TurbulentTundraBehavior;
use App\Domain\Behaviors\Realm\Territories\TwistingIslesOfIllusionsBehavior;
use App\Domain\Behaviors\Realm\Territories\ValleyOfVanishingsBehavior;
use App\Domain\Behaviors\Realm\Territories\WetlandsOfWanderingTormentBehavior;
use App\Domain\Behaviors\Realm\Territories\WoodsOfTheWildBehavior;
use App\Domain\Models\Territory;
use App\Exceptions\UnknownBehaviorException;

class TerritoryBehaviorFactory
{
    public function getBehavior($territoryName): TerritoryBehavior
    {
        switch ($territoryName) {
            case Territory::GARDENS_OF_REDEMPTION:
                return app(GardensOfRedemptionBehavior::class);
            case Territory::WOODS_OF_THE_WILD:
                return app(WoodsOfTheWildBehavior::class);
            case Territory::TWISTING_ISLES_OF_ILLUSIONS:
                return app(TwistingIslesOfIllusionsBehavior::class);
            case Territory::GRASSLANDS_OF_GIANTS:
                return app(GrasslandsOfGiantsBehavior::class);
            case Territory::MENACING_MARCHES:
                return app(MenacingMarchesBehavior::class);
            case Territory::TROPICS_OF_TREPIDATION:
                return app(TropicsOfTrepidationBehavior::class);
            case Territory::PERILOUS_PLAINS:
                return app(PerilousPlainsBehavior::class);
            case Territory::TREACHEROUS_FOREST:
                return app(TreacherousForestBehavior::class);
            case Territory::SAVAGE_SWAMPS:
                return app(SavageSwampsBehavior::class);
            case Territory::DESERT_OF_DESPAIR:
                return app(DesertOfDespairBehavior::class);
            case Territory::HUMBLING_HILLS:
                return app(HumblingHillsBehavior::class);
            case Territory::GULF_OF_SERPENTS:
                return app(GulfOfSerpentsBehavior::class);
            case Territory::CARNIVOROUS_CANYONS:
                return app(CarnivorousCanyonsBehavior::class);
            case Territory::INFERNAL_ISLANDS:
                return app(InfernalIslandsBehavior::class);
            case Territory::JADE_JUNGLE:
                return app(JadeJungleBehavior::class);
            case Territory::SHORES_OF_THE_SHADOWS:
                return app(ShoresOfTheShadowsBehavior::class);
            case Territory::VALLEY_OF_VANISHINGS:
                return app(ValleyOfVanishingsBehavior::class);
            case Territory::PRIMAL_PASSAGE:
                return app(PrimalPassageBehavior::class);
            case Territory::ARCTIC_ARCHIPELAGO:
                return app(ArcticArchipelagoBehavior::class);
            case Territory::BADLANDS_OF_THE_LEVIATHAN:
                return app(BadlandsOfTheLeviathanBehavior::class);
            case Territory::PEAKS_OF_PANDORA:
                return app(PeaksOfPandoraBehavior::class);
            case Territory::CRYPTIC_COAST:
                return app(CrypticCoastBehavior::class);
            case Territory::WETLANDS_OF_WANDERING_TORMENT:
                return app(WetlandsOfWanderingTormentBehavior::class);
            case Territory::BLOODSTONE_BEACHES:
                return app(BloodstoneBeachesBehavior::class);
            case Territory::TURBULENT_TUNDRA:
                return app(TurbulentTundraBehavior::class);
            case Territory::COVES_OF_CORRUPTION:
                return app(CovesOfCorruptionBehavior::class);
            case Territory::MOUNTAINS_OF_MISERY:
                return app(MountainsOfMiseryBehavior::class);
            case Territory::ENCLAVE_OF_ENIGMAS:
                return app(EnclaveOfEnigmasBehavior::class);
            case Territory::SCREAMING_HIGHLANDS_OF_NIGHTMARES:
                return app(ScreamingHighlandsOfNightmaresBehavior::class);
            case Territory::CLOUD_PIERCING_CLIFFS:
                return app(CloudPiercingCliffsBehavior::class);
            case Territory::OUTER_RIM_OF_THE_DEMONIC:
                return app(OuterRimeOfTheDemonicBehavior::class);
        }

        throw new UnknownBehaviorException($territoryName, TerritoryBehavior::class);
    }
}
