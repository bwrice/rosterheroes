<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestCombat;
use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestsAction;
use App\Factories\Models\SideQuestResultFactory;

class ProcessWeeklySideQuestCombatTest extends ProcessWeeklySideQuestsTest
{
    /**
     * @return ProcessWeeklySideQuestCombat
     */
    protected function getDomainAction(): ProcessWeeklySideQuestsAction
    {
        return app(ProcessWeeklySideQuestCombat::class);
    }

    protected function getBaseSideQuestResultFactory(): SideQuestResultFactory
    {
        return SideQuestResultFactory::new();
    }
}
