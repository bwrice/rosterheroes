<?php

namespace Tests\Browser;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SquadCreationTest extends DuskTestCase
{
    /**
     * @test
     */
    public function a_user_can_create_a_squad()
    {
        $user = factory(User::class)->create();
        $squadName = 'My Squad' . random_int(1, 99999);

        $this->browse(function (Browser $browser) use ($user, $squadName) {
            $browser->loginAs($user)
                ->resize(375,667)
                ->visit('/squads/create')
                ->type('squad-name', $squadName)
                ->press('squad-submit');
        });

        $squad = Squad::query()->where('name', '=', $squadName)->first();
        $this->assertNotNull($squad);
        $this->assertEquals($user->id, $squad->user_id);
    }
}
