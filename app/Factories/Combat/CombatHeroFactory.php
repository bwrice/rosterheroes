<?php


namespace App\Factories\Combat;


use App\Domain\Behaviors\HeroClasses\WarriorBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\HealthBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ManaBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\StaminaBehavior;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Factories\Models\HeroFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CombatHeroFactory extends AbstractCombatantFactory
{
    /** @var string|null */
    protected $heroUuid;

    /** @var HeroFactory */
    protected $heroFactory;

    protected $health;

    protected $stamina;

    protected $mana;

    protected $protection;

    protected $blockChancePercent;

    /** @var Collection|null */
    protected $heroCombatAttackFactories;

    public function create()
    {
        $combatPosition = $this->getCombatPosition();
        $heroUuid = $this->getHeroUuid();
        $heroCombatAttacks = $this->getHeroCombatAttacks($heroUuid);

        return new CombatHero(
            $heroUuid,
            is_null($this->health) ? 800 : $this->health,
            is_null($this->stamina) ? 500 : $this->stamina,
            is_null($this->mana) ? 400 : $this->mana,
            is_null($this->protection) ? 100 : $this->protection,
            is_null($this->blockChancePercent) ? 10: $this->blockChancePercent,
            $combatPosition,
            $heroCombatAttacks
        );
    }

    public function forHero(string $heroUuid)
    {
        $clone = clone $this;
        $clone->heroUuid = $heroUuid;
        return $clone;
    }

    public function withMana(int $mana)
    {
        $clone = clone $this;
        $clone->mana = $mana;
        return $clone;
    }

    public function withStamina(int $stamina)
    {
        $clone = clone $this;
        $clone->stamina = $stamina;
        return $clone;
    }

    public function withHealth(int $health)
    {
        $clone = clone $this;
        $clone->health = $health;
        return $clone;
    }

    public function withProtection(int $protection)
    {
        $clone = clone $this;
        $clone->protection = $protection;
        return $clone;
    }

    public function withBlockChancePercent(float $blockChancePercent)
    {
        $clone = clone $this;
        $clone->blockChancePercent = $blockChancePercent;
        return $clone;
    }

    public function fromHeroFactory(HeroFactory $heroFactory)
    {
        $clone = clone $this;
        $clone->heroFactory = $heroFactory;
        return $clone;
    }

    public function withHeroCombatAttacks(Collection $heroCombatAttackFactories)
    {
        $clone = clone $this;
        $clone->heroCombatAttackFactories = $heroCombatAttackFactories;
        return $clone;
    }

    protected function getHeroUuid()
    {
        $heroFactory = $this->heroFactory ?: HeroFactory::new();
        return $heroFactory->create()->uuid;
    }

    protected function getHeroCombatAttacks(string $heroUuid)
    {
        if ($this->heroCombatAttackFactories) {
            return $this->heroCombatAttackFactories->map(function (HeroCombatAttackFactory $factory) use ($heroUuid) {
                return $factory->forHero($heroUuid)->create();
            });
        }
        return collect();
    }
}
