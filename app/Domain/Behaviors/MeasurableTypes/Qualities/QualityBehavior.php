<?php


namespace App\Domain\Behaviors\MeasurableTypes\Qualities;


use App\Domain\Behaviors\MeasurableTypes\MeasurableGroups\MeasurableGroup;
use App\Domain\Behaviors\MeasurableTypes\MeasurableGroups\QualityMeasurableGroup;
use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;

abstract class QualityBehavior extends MeasurableTypeBehavior
{
    public function __construct(QualityMeasurableGroup $qualityGroup)
    {
        parent::__construct($qualityGroup);
    }
}
