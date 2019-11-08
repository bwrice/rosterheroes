<?php


namespace App\Domain\Behaviors\Realm;


use App\Domain\Behaviors\Realm\Continents\CentralJagonethBehavior;
use App\Domain\Behaviors\Realm\Continents\ContinentBehavior;
use App\Domain\Behaviors\Realm\Continents\DemauxorBehavior;
use App\Domain\Behaviors\Realm\Continents\EastWozulBehavior;
use App\Domain\Behaviors\Realm\Continents\FetroyaBehavior;
use App\Domain\Behaviors\Realm\Continents\NorthJagonethBehavior;
use App\Domain\Behaviors\Realm\Continents\SouthJagonethBehavior;
use App\Domain\Behaviors\Realm\Continents\VindoberonBehavior;
use App\Domain\Behaviors\Realm\Continents\WestWozulBehavior;
use App\Domain\Models\Continent;
use App\Exceptions\UnknownBehaviorException;

class ContinentBehaviorFactory
{
    public function getBehavior($continentName): ContinentBehavior
    {
        switch ($continentName) {
            case Continent::FETROYA:
                return app(FetroyaBehavior::class);
            case Continent::EAST_WOZUL:
                return app(EastWozulBehavior::class);
            case Continent::WEST_WOZUL:
                return app(WestWozulBehavior::class);
            case Continent::NORTH_JAGONETH:
                return app(NorthJagonethBehavior::class);
            case Continent::CENTRAL_JAGONETH:
                return app(CentralJagonethBehavior::class);
            case Continent::SOUTH_JAGONETH:
                return app(SouthJagonethBehavior::class);
            case Continent::VINDOBERON:
                return app(VindoberonBehavior::class);
            case Continent::DEMAUXOR:
                return app(DemauxorBehavior::class);
        }
        throw new UnknownBehaviorException((string) $continentName, ContinentBehavior::class);
    }
}
