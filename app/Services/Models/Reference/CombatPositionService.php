<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\TargetRanges\BackLineBehavior;
use App\Domain\Behaviors\TargetRanges\CombatPositionBehavior;
use App\Domain\Behaviors\TargetRanges\FrontLineBehavior;
use App\Domain\Behaviors\TargetRanges\HighGroundBehavior;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class CombatPositionService extends ReferenceService
{

    public function __construct()
    {
        $this->behaviors[CombatPosition::FRONT_LINE] = app(FrontLineBehavior::class);
        $this->behaviors[CombatPosition::BACK_LINE] = app(BackLineBehavior::class);
        $this->behaviors[CombatPosition::HIGH_GROUND] = app(HighGroundBehavior::class);
    }

    protected function all(): Collection
    {
        return CombatPosition::all();
    }
}
