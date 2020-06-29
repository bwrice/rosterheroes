<?php


namespace App\Domain\Collections;


use App\Domain\Models\Measurable;
use Illuminate\Database\Eloquent\Collection;

class MeasurableCollection extends Collection
{
    public function experienceSpentOnRaising(): int
    {
        return (int) $this->sum(function (Measurable $measurable) {
            return $measurable->spentOnRaising();
        });
    }

    /**
     * @return mixed
     */
    public function currentAmountAverage()
    {
        return $this->average(function (Measurable $measurable) {
            return $measurable->getCurrentAmount();
        });
    }
}
