<?php


namespace App\Domain\Behaviors\Realm;


use App\Domain\Models\Continent;
use App\Exceptions\UnknownBehaviorException;

class ContinentBehaviorFactory
{
    public function getBehavior($continentName): ContinentBehavior
    {
        switch ($continentName) {
            case Continent::FETROYA:
                return new ContinentBehavior(
                    new RealmBehavior('#b2b800', [
                        'pan_x' => 178,
                        'pan_y' => 18,
                        'zoom_x' => 130,
                        'zoom_y' => 99
                    ]));
            case Continent::EAST_WOZUL:
                return new ContinentBehavior(
                    new RealmBehavior('#d18c02', [
                        'pan_x' => 185,
                        'pan_y' => 70,
                        'zoom_x' => 130,
                        'zoom_y' => 99
                    ]));
            case Continent::WEST_WOZUL:
                return new ContinentBehavior(
                    new RealmBehavior('#c12907', [
                        'pan_x' => 135,
                        'pan_y' => 99,
                        'zoom_x' => 150,
                        'zoom_y' => 114
                    ]));
            case Continent::NORTH_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#46a040', [
                        'pan_x' => 60,
                        'pan_y' => 3,
                        'zoom_x' => 160,
                        'zoom_y' => 122
                    ]));
            case Continent::CENTRAL_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#3e81a5', [
                        'pan_x' => 48,
                        'pan_y' => 48,
                        'zoom_x' => 130,
                        'zoom_y' => 99
                    ]));
            case Continent::SOUTH_JAGONETH:
                return new ContinentBehavior(
                    new RealmBehavior('#6834aa', [
                        'pan_x' => 24,
                        'pan_y' => 74,
                        'zoom_x' => 172,
                        'zoom_y' => 131
                    ]));
            case Continent::VINDOBERON:
                return new ContinentBehavior(
                    new RealmBehavior('#4f547a', [
                        'pan_x' => -48,
                        'pan_y' => 8,
                        'zoom_x' => 184,
                        'zoom_y' => 141
                    ]));
            case Continent::DEMAUXOR:
                return new ContinentBehavior(
                    new RealmBehavior('#9e1284', [
                        'pan_x' => 0,
                        'pan_y' => 126,
                        'zoom_x' => 160,
                        'zoom_y' => 121
                    ]));
        }
        throw new UnknownBehaviorException((string) $continentName, ContinentBehavior::class);
    }
}
