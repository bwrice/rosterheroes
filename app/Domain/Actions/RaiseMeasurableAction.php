<?php


namespace App\Domain\Actions;


use App\Aggregates\MeasurableAggregate;
use App\Domain\Models\Measurable;
use App\Exceptions\RaiseMeasurableException;

class RaiseMeasurableAction
{

    /**
     * @param Measurable $measurable
     * @param int $amount
     * @return Measurable
     * @throws RaiseMeasurableException
     */
    public function execute(Measurable $measurable, int $amount): Measurable
    {
        if ($amount < 1) {
            $message =  "Raise amount must be positive. " . $amount . " given.";
            $code = RaiseMeasurableException::CODE_NON_POSITIVE_NUMBER;
            throw new RaiseMeasurableException($measurable, $amount, $message, $code);
        }

        $costToRaise = $measurable->getCostToRaise($amount);
        $availableExp = $measurable->hero->availableExperience();
        if ($costToRaise > $availableExp) {
            $message = $costToRaise .  " experience required, but only " . $availableExp . " available";
            $code = RaiseMeasurableException::INSUFFICIENT_EXPERIENCE;
            throw new RaiseMeasurableException($measurable, $amount, $message, $code);
        }

        /** @var MeasurableAggregate $aggregate */
        $aggregate = MeasurableAggregate::retrieve($measurable->uuid);
        $aggregate->raiseMeasurable($amount)->persist();
        return $measurable->fresh();
    }

}
