<?php

namespace App\Console\Commands;

use App\Domain\Actions\NPC\CreateNPCSquad;
use Illuminate\Console\Command;

class CreateNPCSquadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npc:create-squads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create NPC Squads';

    /**
     * @param CreateNPCSquad $createNPCSquad
     */
    public function handle(CreateNPCSquad $createNPCSquad)
    {
        $amount = $this->ask('How many?');
        for ($i = 1; $i <= $amount; $i++) {
            $createNPCSquad->execute();
        }
    }
}
