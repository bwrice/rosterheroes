<?php


namespace App\Domain\Actions\NPC;



use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Models\Squad;
use App\Jobs\OpenNPCChestJob;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Bus;

/**
 * Class AutoManageNPC
 * @package App\Domain\Actions\NPC
 *
 * @method execute(Squad $squad, float $triggerChance, int $maxDelayMinutes)
 */
class AutoManageNPC extends NPCAction
{
    protected BuildNPCActionTrigger $buildNPCActionTrigger;

    public function __construct(BuildNPCActionTrigger $buildNPCActionTrigger)
    {
        $this->buildNPCActionTrigger = $buildNPCActionTrigger;
    }

    /**
     * @param float $triggerChance
     * @param int $maxDelayMinutes
     * @throws \Exception
     */
    public function handleExecute(float $triggerChance, int $maxDelayMinutes)
    {
        /** @var CarbonInterface $initialDelay */
        $initialDelay = now()->addMinutes(rand(1, $maxDelayMinutes));
        $trigger = $this->buildNPCActionTrigger->execute($this->npc, $triggerChance);
        $jobs = collect();
        $secondsDelay = 0;

        $trigger->getActions()->each(function ($data, $key) use (&$jobs, &$secondsDelay, $initialDelay) {
            $jobsToAdd = collect();
            switch ($key) {
                case NPCActionTrigger::KEY_OPEN_CHESTS:
                $jobsToAdd = $this->getOpenChestJobs($data);
                break;
            }
            $jobsToAdd->each(function ($job) use(&$secondsDelay, $initialDelay) {
                /** @var Queueable $job */
                $secondsDelay += rand(4, 60);
                $job->delay($initialDelay->addSeconds($secondsDelay));
            });
            $jobs = $jobs->merge($jobsToAdd);
        });

        if ($jobs->isNotEmpty()) {
            Bus::chain($jobs->toArray())->dispatch();
        }
    }

    protected function getOpenChestJobs($actionData)
    {
        $jobsToAdd = collect();
        $chestsCount = $actionData['chests_count'];
        if ($chestsCount > 0) {
            for ($i = 1; $i <= $actionData['chests_count']; $i++) {
                $jobsToAdd->push(new OpenNPCChestJob($this->npc));
            }
        }
        return $jobsToAdd;
    }
}
