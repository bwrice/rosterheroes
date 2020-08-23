<?php

namespace App\Jobs;

use App\Domain\Actions\NPC\MoveNPCToProvince;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoveNPCToProvinceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var Squad
     */
    public $npc;
    /**
     * @var Province
     */
    public $province;

    public function __construct(Squad $npc, Province $province)
    {
        //
        $this->npc = $npc;
        $this->province = $province;
    }

    /**
     * @param MoveNPCToProvince $domainAction
     * @throws \Exception
     */
    public function handle(MoveNPCToProvince $domainAction)
    {
        $domainAction->execute($this->npc, $this->province);
    }
}
