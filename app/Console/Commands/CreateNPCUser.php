<?php

namespace App\Console\Commands;

use App\Domain\Actions\CreateUserAction;
use Illuminate\Console\Command;

class CreateNPCUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npc:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the user account for handling NPCs';

    /**
     * @param CreateUserAction $createUserAction
     */
    public function handle(CreateUserAction $createUserAction)
    {
        $user = $createUserAction->execute(config('npc.user.email'), 'NPC', config('npc.user.password'));
        $user->markEmailAsVerified();
        // clear email subs
        $user->emailSubscriptions()->sync([]);
        $this->info("NPC User Created with email: " . $user->email);
    }
}
