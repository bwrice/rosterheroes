<?php

namespace Tests\Feature;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function a_new_squad_can_be_created()
    {
        $this->withoutExceptionHandling();

        Passport::actingAs(factory(User::class)->create());

        $name = 'TestSquad' . rand(1,999999);

        $response = $this->json('POST','api/v1/squads', [
           'name' => $name
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'name' => $name
            ]
        ]);
    }
}
