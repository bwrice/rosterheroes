<?php

namespace Tests\Feature;

use App\Domain\Behaviors\MeasurableTypes\Qualities\QualityBehavior;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeasurableTypeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function each_stat_type_has_a_single_matching_measurable_type_of_group_quality()
    {
        $statTypes = StatType::all();

        $qualities = MeasurableType::all()->filter(function (MeasurableType $measurableType) {
            return $measurableType->getBehavior()->getGroupName() === QualityBehavior::GROUP_NAME;
        });

        $statTypes->each(function (StatType $statType) use ($qualities) {
            $matchingQualities = $qualities->filter(function (MeasurableType $measurableType) use ($statType) {
                return in_array($statType->name, $measurableType->getBehavior()->getStatTypeNames());
            });
            $count = $matchingQualities->count();
            $this->assertEquals(1, $count, $statType->name . ' has ' . $count . ' matching qualities');
        });
    }
}
