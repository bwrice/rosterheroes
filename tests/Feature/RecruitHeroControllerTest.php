<?php

namespace Tests\Feature;

use App\Domain\Actions\RecruitHero;
use App\Domain\Models\User;
use App\Exceptions\RecruitHeroException;
use App\Facades\CurrentWeek;
use App\Factories\Models\HeroFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RecruitHeroControllerTest extends RecruitHeroTest
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_be_unauthorized_if_the_user_does_not_own_the_squad_recruiting()
    {
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('POST', 'api/v1/squads/' . $this->squad->slug . '/recruitment-camps/' . $this->recruitmentCamp->slug . '/recruit', [
            'heroPostType' => $this->heroPostType->id,
            'heroRace' => $this->heroRace->id,
            'heroClass' => $this->heroClass->id,
            'heroName' => $this->heroName
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @param $invalidHeroName
     * @dataProvider provides_the_hero_name_for_the_recruit_must_be_valid
     */
    public function the_hero_name_for_the_recruit_must_be_valid($invalidHeroName)
    {
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        Passport::actingAs($this->squad->user);

        $response = $this->json('POST', 'api/v1/squads/' . $this->squad->slug . '/recruitment-camps/' . $this->recruitmentCamp->slug . '/recruit', [
            'heroPostType' => $this->heroPostType->id,
            'heroRace' => $this->heroRace->id,
            'heroClass' => $this->heroClass->id,
            'heroName' => $invalidHeroName
        ]);

        $response->assertStatus(422);
        $this->assertTrue(array_key_exists('heroName', $response->json('errors')));
    }

    public function provides_the_hero_name_for_the_recruit_must_be_valid()
    {
        return [
            [
                'invalidHeroName' => 'F'
            ],
            [
                'invalidHeroName' => null
            ],
            [
                'invalidHeroName' => 'ABCD&&&'
            ],
            [
                'invalidHeroName' => 'way too long string ojwoefoijweofijwoefijwoeifjo'
            ],
        ];
    }

    /**
     * @test
     */
    public function it_will_fail_to_recruit_a_hero_with_an_existing_hero_name()
    {
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        Passport::actingAs($this->squad->user);

        $existingHeroName = HeroFactory::new()->create()->name;

        $response = $this->json('POST', 'api/v1/squads/' . $this->squad->slug . '/recruitment-camps/' . $this->recruitmentCamp->slug . '/recruit', [
            'heroPostType' => $this->heroPostType->id,
            'heroRace' => $this->heroRace->id,
            'heroClass' => $this->heroClass->id,
            'heroName' => $existingHeroName
        ]);

        $response->assertStatus(422);
        $this->assertTrue(array_key_exists('heroName', $response->json('errors')));
    }

    /**
     * @test
     */
    public function it_will_return_a_validation_exception_if_recruitment_exception_thrown()
    {
        $message = (string) Str::random();
        $exception = new RecruitHeroException(
            $this->squad,
            $this->recruitmentCamp,
            $this->heroPostType,
            $this->heroRace,
            $this->heroClass,
            $message
        );

        $this->mock(RecruitHero::class)->shouldReceive('execute')->andThrow($exception);

        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        Passport::actingAs($this->squad->user);

        $response = $this->json('POST', 'api/v1/squads/' . $this->squad->slug . '/recruitment-camps/' . $this->recruitmentCamp->slug . '/recruit', [
            'heroPostType' => $this->heroPostType->id,
            'heroRace' => $this->heroRace->id,
            'heroClass' => $this->heroClass->id,
            'heroName' => $this->heroName
        ]);

        $response->assertStatus(422);
        $errors = $response->json('errors');
        $this->assertTrue(array_key_exists('recruit', $errors));
        $this->assertEquals($errors['recruit'][0], $message);
    }
}
