<?php

namespace App\Jobs;

use App\Domain\Actions\BuildAttackSnapshotAction;
use App\Domain\Models\Attack;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BuildAttackSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Attack
     */
    public $attack;

    /**
     * BuildAttackSnapshotJob constructor.
     * @param Attack $attack
     */
    public function __construct(Attack $attack)
    {
        $this->attack = $attack;
    }

    /**
     * @param BuildAttackSnapshotAction $domainAction
     */
    public function handle(BuildAttackSnapshotAction $domainAction)
    {
        $domainAction->execute($this->attack);
    }
}
