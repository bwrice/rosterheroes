<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\FindMeasurablesToRaise;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Factories\Models\HeroFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FindMeasurablesToRaiseTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return FindMeasurablesToRaise
     */
    protected function getDomainAction()
    {
        return app(FindMeasurablesToRaise::class);
    }

    /**
     * @test
     */
    public function it_will_return_an_empty_collection_if_not_enough_experience_to_raise_any_measurables()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();
        $strength = $hero->getMeasurable(MeasurableType::STRENGTH);
        $strength->amount_raised = 100;
        $strength->save();

        $hero = $hero->fresh();

        $this->assertLessThanOrEqual(0, $hero->availableExperience());

        $measurablesToRaise = $this->getDomainAction()->execute($hero);
        $this->assertTrue($measurablesToRaise->isEmpty());
    }

    /**
     * @test
     */
    public function it_will_return_every_measurable_with_a_positive_raise_amount_if_enough_experience_available()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $hero->squad->experience += 9999;
        $hero->squad->save();

        $measurablesToRaise = $this->getDomainAction()->execute($hero);
        MeasurableType::all()->each(function (MeasurableType $measurableType) use ($measurablesToRaise) {
            $match = $measurablesToRaise->first(function ($raiseArray) use ($measurableType) {
                /** @var Measurable $measurable */
                $measurable = $raiseArray['measurable'];
                return $measurable->measurableType->name === $measurableType->name;
            });
            $this->assertNotNull($match, "No raise array found for " . $measurableType->name);
            $this->assertGreaterThan(0, $match['amount']);
        });
    }

    /**
     * @test
     */
    public function it_will_not_return_raise_amounts_more_than_the_available_experience_of_the_hero()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $hero->squad->experience += rand(500, 9999);
        $hero->squad->save();

        $availableExperience = $hero->availableExperience();

        $measurablesToRaise = $this->getDomainAction()->execute($hero);

        $sumOfCost = $measurablesToRaise->sum(function ($raiseArray) {
            /** @var Measurable $measurable */
            $measurable = $raiseArray['measurable'];
            return $measurable->getCostToRaise($raiseArray['amount']);
        });

        $this->assertLessThanOrEqual($availableExperience, $sumOfCost);
    }

    /**
     * @test
     */
    public function it_will_prioritize_spending_more_on_valor_than_health_measurable()
    {
        $hero = HeroFactory::new()->withMeasurables()->create();

        $hero->squad->experience += 9999;
        $hero->squad->save();

        $measurablesToRaise = $this->getDomainAction()->execute($hero);

        $valorRaiseArray = $measurablesToRaise->first(function ($raiseArray) {
            /** @var Measurable $measurable */
            $measurable = $raiseArray['measurable'];
            return $measurable->measurableType->name === MeasurableType::VALOR;
        });

        /** @var Measurable $valor */
        $valor = $valorRaiseArray['measurable'];
        $valorRaiseCost = $valor->getCostToRaise($valorRaiseArray['amount']);

        $healthRaiseArray = $measurablesToRaise->first(function ($raiseArray) {
            /** @var Measurable $measurable */
            $measurable = $raiseArray['measurable'];
            return $measurable->measurableType->name === MeasurableType::HEALTH;
        });

        /** @var Measurable $health */
        $health = $healthRaiseArray['measurable'];
        $healthRaiseCost = $health->getCostToRaise($healthRaiseArray['amount']);
        $this->assertLessThan($valorRaiseCost, $healthRaiseCost);
    }
}
