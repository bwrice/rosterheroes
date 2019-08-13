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
        // TODO: extend BeyondCode DuskTestCase and try the dashboard sometime in the future when they fix the package

        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {

            $squadName = 'My Squad ' . random_int(1, 9999999);

            $browser = $browser->loginAs($user)
                ->visit('/squads/create')
                ->resize(375, 667)
                ->type('squad-name', $squadName)
                ->press('squad-submit')->pause(1500);

            $baseHeroName = 'My Hero ';

            $browser = $browser->press('label[for=warrior-1]')
                ->type('hero-name-1', $baseHeroName . random_int(1, 9999999))
                ->press('hero-submit-1');
        });
    }
}
