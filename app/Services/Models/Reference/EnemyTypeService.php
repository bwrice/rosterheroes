<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Domain\Behaviors\EnemyTypes\GargoyleBehavior;
use App\Domain\Behaviors\EnemyTypes\GiantBehavior;
use App\Domain\Behaviors\EnemyTypes\GolemBehavior;
use App\Domain\Behaviors\EnemyTypes\ImpBehavior;
use App\Domain\Behaviors\EnemyTypes\TrollBehavior;
use App\Domain\Behaviors\EnemyTypes\UndeadBehavior;
use App\Domain\Behaviors\EnemyTypes\VampireBehavior;
use App\Domain\Behaviors\EnemyTypes\WerewolfBehavior;
use App\Domain\Behaviors\EnemyTypes\WitchBehavior;
use App\Domain\Models\EnemyType;
use Illuminate\Database\Eloquent\Collection;

class EnemyTypeService extends ReferenceService
{
    protected string $behaviorClass = EnemyTypeBehavior::class;

    public function __construct()
    {
        $this->behaviors[EnemyType::GARGOYLE] = app(GargoyleBehavior::class);
        $this->behaviors[EnemyType::GIANT] = app(GiantBehavior::class);
        $this->behaviors[EnemyType::GOLEM] = app(GolemBehavior::class);
        $this->behaviors[EnemyType::IMP] = app(ImpBehavior::class);
        $this->behaviors[EnemyType::TROLL] = app(TrollBehavior::class);
        $this->behaviors[EnemyType::UNDEAD] = app(UndeadBehavior::class);
        $this->behaviors[EnemyType::VAMPIRE] = app(VampireBehavior::class);
        $this->behaviors[EnemyType::WEREWOLF] = app(WerewolfBehavior::class);
        $this->behaviors[EnemyType::WITCH] = app(WitchBehavior::class);
    }

    protected function all(): Collection
    {
        return EnemyType::all();
    }
}
