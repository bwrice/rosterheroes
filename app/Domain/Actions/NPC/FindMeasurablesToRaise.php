<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Collection;

class FindMeasurablesToRaise
{
    protected int $availableExperience = 0;

    protected array $measurableRaiseAmounts = [
        MeasurableType::STRENGTH => 0,
        MeasurableType::VALOR => 0,
        MeasurableType::AGILITY => 0,
        MeasurableType::FOCUS => 0,
        MeasurableType::APTITUDE => 0,
        MeasurableType::INTELLIGENCE => 0,
        MeasurableType::HEALTH => 0,
        MeasurableType::STAMINA => 0,
        MeasurableType::MANA => 0,
        MeasurableType::PASSION => 0,
        MeasurableType::BALANCE => 0,
        MeasurableType::HONOR => 0,
        MeasurableType::PRESTIGE => 0,
        MeasurableType::WRATH => 0
    ];

    protected array $measurableRaiseCosts = [
        MeasurableType::STRENGTH => 0,
        MeasurableType::VALOR => 0,
        MeasurableType::AGILITY => 0,
        MeasurableType::FOCUS => 0,
        MeasurableType::APTITUDE => 0,
        MeasurableType::INTELLIGENCE => 0,
        MeasurableType::HEALTH => 0,
        MeasurableType::STAMINA => 0,
        MeasurableType::MANA => 0,
        MeasurableType::PASSION => 0,
        MeasurableType::BALANCE => 0,
        MeasurableType::HONOR => 0,
        MeasurableType::PRESTIGE => 0,
        MeasurableType::WRATH => 0
    ];

    /**
     * @param Hero $hero
     * @return Collection
     */
    public function execute(Hero $hero)
    {
        $this->availableExperience = $hero->availableExperience();
        $measurables = $hero->measurables()->with('measurableType')->get();
        $heroClassName = $hero->heroClass->name;

        $i = 1;
        do {

            $mapped = $measurables->map(function (Measurable $measurable) {
                $measurableTypeName = $measurable->measurableType->name;
                $raiseAmount = $this->measurableRaiseAmounts[$measurableTypeName] + 1;
                $currentCost = $this->measurableRaiseCosts[$measurableTypeName];
                return [
                    'cost_to_raise' => $measurable->getCostToRaise($raiseAmount) - $currentCost,
                    'measurable_type_name' => $measurable->measurableType->name
                ];
            });

            $filtered = $mapped->filter(function ($costArray) {
                return $costArray['cost_to_raise'] <= $this->getUnusedAvailableExperience();
            });

            if ($filtered->isNotEmpty()) {

                // Divide weight by cost-to-raise to determine our priority on which measurable type to raise
                $costArray = $filtered->sortByDesc(function ($costArray) use ($heroClassName) {
                    $weight = $this->measurableWeight()[$heroClassName][$costArray['measurable_type_name']];
                    return $weight/$costArray['cost_to_raise'];
                })->first();

                // Increment raise count for selected highest priority measurable-type
                $this->measurableRaiseAmounts[$costArray['measurable_type_name']] += 1;
                $this->measurableRaiseCosts[$costArray['measurable_type_name']] += $costArray['cost_to_raise'];
            }

            $i++;
        } while ($i <= 100 && $filtered->isNotEmpty());

        /*
         * Return measurable-raise-amounts mapped into a collection of the amount to raise
         * and the matching measurable of the hero
         */
        return collect($this->measurableRaiseAmounts)->filter(function ($raiseAmount) {
            return $raiseAmount > 0;
        })->map(function ($raiseAmount, $measurableTypeName) use ($measurables) {
            return [
                'amount' => $raiseAmount,
                'measurable' => $measurables->first(function (Measurable $measurable) use ($measurableTypeName) {
                    return $measurable->measurableType->name === $measurableTypeName;
                })
            ];
        });
    }

    protected function getUnusedAvailableExperience()
    {
        return $this->availableExperience - collect($this->measurableRaiseCosts)->sum();
    }

    protected function measurableWeight()
    {
        return [
            HeroClass::WARRIOR => [
                MeasurableType::STRENGTH => 10,
                MeasurableType::VALOR => 15,
                MeasurableType::AGILITY => 6,
                MeasurableType::FOCUS => 6,
                MeasurableType::APTITUDE => 6,
                MeasurableType::INTELLIGENCE => 10,
                MeasurableType::HEALTH => 2,
                MeasurableType::STAMINA => 1,
                MeasurableType::MANA => 1,
                MeasurableType::PASSION => 4,
                MeasurableType::BALANCE => 4,
                MeasurableType::HONOR => 4,
                MeasurableType::PRESTIGE => 4,
                MeasurableType::WRATH => 4
            ],
            HeroClass::RANGER => [
                MeasurableType::STRENGTH => 7,
                MeasurableType::VALOR => 8,
                MeasurableType::AGILITY => 12,
                MeasurableType::FOCUS => 10,
                MeasurableType::APTITUDE => 6,
                MeasurableType::INTELLIGENCE => 10,
                MeasurableType::HEALTH => 1,
                MeasurableType::STAMINA => 2,
                MeasurableType::MANA => 1,
                MeasurableType::PASSION => 4,
                MeasurableType::BALANCE => 4,
                MeasurableType::HONOR => 4,
                MeasurableType::PRESTIGE => 4,
                MeasurableType::WRATH => 4
            ],
            HeroClass::SORCERER => [
                MeasurableType::STRENGTH => 6,
                MeasurableType::VALOR => 10,
                MeasurableType::AGILITY => 4,
                MeasurableType::FOCUS => 8,
                MeasurableType::APTITUDE => 10,
                MeasurableType::INTELLIGENCE => 12,
                MeasurableType::HEALTH => 2,
                MeasurableType::STAMINA => 1,
                MeasurableType::MANA => 2,
                MeasurableType::PASSION => 4,
                MeasurableType::BALANCE => 4,
                MeasurableType::HONOR => 4,
                MeasurableType::PRESTIGE => 4,
                MeasurableType::WRATH => 4
            ],
        ];
    }
}
