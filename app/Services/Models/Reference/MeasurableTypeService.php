<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\MeasurableTypes\Attributes\AgilityBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\AptitudeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\FocusBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\IntelligenceBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\StrengthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Attributes\ValorBehavior;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\BalanceBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\HonorBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\PassionBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\PrestigeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Qualities\WrathBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\HealthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ManaBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\StaminaBehavior;
use App\Domain\Models\MeasurableType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MeasurableTypeService
 * @package App\Services\Models\Reference
 *
 * @method MeasurableTypeBehavior getBehavior($identifier)
 */
class MeasurableTypeService extends ReferenceService
{
    protected string $behaviorClass = MeasurableTypeBehavior::class;

    public function __construct()
    {
        $this->behaviors[MeasurableType::STRENGTH] = app(StrengthBehavior::class);
        $this->behaviors[MeasurableType::VALOR] = app(ValorBehavior::class);
        $this->behaviors[MeasurableType::AGILITY] = app(AgilityBehavior::class);
        $this->behaviors[MeasurableType::FOCUS] = app(FocusBehavior::class);
        $this->behaviors[MeasurableType::APTITUDE] = app(AptitudeBehavior::class);
        $this->behaviors[MeasurableType::INTELLIGENCE] = app(IntelligenceBehavior::class);
        $this->behaviors[MeasurableType::HEALTH] = app(HealthBehavior::class);
        $this->behaviors[MeasurableType::STAMINA] = app(StaminaBehavior::class);
        $this->behaviors[MeasurableType::MANA] = app(ManaBehavior::class);
        $this->behaviors[MeasurableType::PASSION] = app(PassionBehavior::class);
        $this->behaviors[MeasurableType::BALANCE] = app(BalanceBehavior::class);
        $this->behaviors[MeasurableType::HONOR] = app(HonorBehavior::class);
        $this->behaviors[MeasurableType::PRESTIGE] = app(PrestigeBehavior::class);
        $this->behaviors[MeasurableType::WRATH] = app(WrathBehavior::class);
    }

    protected function all(): Collection
    {
        return MeasurableType::all();
    }
}
