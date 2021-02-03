<?php


namespace App\Domain\Actions;


use App\Domain\Models\Measurable;
use App\Exceptions\RaiseMeasurableException;
use App\Facades\CurrentWeek;

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
        if (CurrentWeek::adventuringLocked()) {
            $code = RaiseMeasurableException::CODE_WEEK_LOCKED;
            throw new RaiseMeasurableException($measurable, $amount, "Week is currently locked", $code);
        }
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

        $measurable->amount_raised += $amount;
        $measurable->save();
        return $measurable->fresh();
    }

}
