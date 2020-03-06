<?php


namespace App\Domain\Actions\WeekFinalizing;


interface FinalizeWeekDomainAction
{
    public function execute(int $finalizeWeekStep, array $extra = []);
}
