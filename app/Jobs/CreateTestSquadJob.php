<?php

namespace App\Jobs;

use App\Domain\Actions\Testing\CreateTestSquadAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTestSquadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int
     */
    public $testID;

    /**
     * CreateTestSquadJob constructor.
     * @param int $testID
     */
    public function __construct(int $testID)
    {
        $this->testID = $testID;
    }

    /**
     * @param CreateTestSquadAction $createTestSquadAction
     */
    public function handle(CreateTestSquadAction $createTestSquadAction)
    {
        $createTestSquadAction->execute($this->testID);
    }
}
