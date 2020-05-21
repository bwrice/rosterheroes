<?php

namespace Tests\Feature;

use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
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

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_name_is_white_space_invalid()
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->assertEquals(0, $user->squads()->count());

        $whiteSpaceName = '       B';

        try {
            $response = $this->json('POST','api/v1/squads', [
                'name' => $whiteSpaceName
            ]);

        } catch (ValidationException $exception) {
            $nameErrors = $exception->validator->errors()->get('name');
            $this->assertNotEmpty($nameErrors);
            $this->assertEquals(0, $user->squads()->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }
    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_name_has_special_characters()
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->assertEquals(0, $user->squads()->count());

        $specialCharName = 'ValidExcept&';

        try {
            $response = $this->json('POST','api/v1/squads', [
                'name' => $specialCharName
            ]);

        } catch (ValidationException $exception) {
            $nameErrors = $exception->validator->errors()->get('name');
            $this->assertNotEmpty($nameErrors);
            $this->assertEquals(0, $user->squads()->count());
            return;
        }

        $this->fail("Exception Not Thrown");
    }
}
