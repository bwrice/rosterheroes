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

        $statTypes->each(function (StatType $statType) {
            $measurableTypes = $statType->measurableTypes;
            $count = $measurableTypes->count();
            $this->assertEquals(1, $count, $statType->name . ' has ' . $count . ' matching qualities');
            /** @var MeasurableType $quality */
            $quality = $measurableTypes->first();
            $this->assertEquals($quality->getMeasurableGroup(), QualityBehavior::GROUP_NAME);
        });
    }
}
