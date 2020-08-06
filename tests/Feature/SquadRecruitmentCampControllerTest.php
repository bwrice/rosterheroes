<?php

namespace Tests\Feature;

use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\User;
use App\Factories\Models\RecruitmentCampFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SquadRecruitmentCampControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_be_unauthorized_if_the_squad_does_not_belong_to_the_user()
    {
        $squad = SquadFactory::new()->create();
        $recruitmentCamp = RecruitmentCampFactory::new()->withProvinceID($squad->province_id)->create();

        // Acting as different user
        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/recruitment-camps/' . $recruitmentCamp->slug);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_be_unauthorized_if_the_squad_is_not_at_the_same_province_as_the_recruitment_camp()
    {
        $squad = SquadFactory::new()->create();
        $diffProvinceID = $squad->province_id === 1 ? 2 : $squad->province_id - 1;
        $recruitmentCamp = RecruitmentCampFactory::new()->withProvinceID($diffProvinceID)->create();

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/recruitment-camps/' . $recruitmentCamp->slug);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_will_return_the_expected_recruitment_camp_response()
    {
        $this->withoutExceptionHandling();
        $squad = SquadFactory::new()->create();
        $recruitmentCamp = RecruitmentCampFactory::new()->withProvinceID($squad->province_id)->create();

        $heroClasses = HeroClass::all();
        $heroPostTypes = HeroPostType::all();
        $recruitmentCamp->heroClasses()->saveMany($heroClasses);
        $recruitmentCamp->heroPostTypes()->saveMany($heroPostTypes);

        Passport::actingAs($squad->user);

        $response = $this->json('GET', 'api/v1/squads/' . $squad->slug . '/recruitment-camps/' . $recruitmentCamp->slug);

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertEquals($data['uuid'], $recruitmentCamp->uuid);

        $this->assertEquals(count($data['heroClassIDs']), $heroClasses->count());

        $heroPostTypeArrays = $data['heroPostTypes'];
        $this->assertEquals(count($heroPostTypeArrays), $heroPostTypes->count());

        foreach ($heroPostTypeArrays as $heroPostTypeArray) {
            /** @var HeroPostType $heroPostType */
            $heroPostType = $heroPostTypes->first(function (HeroPostType $heroPostType) use ($heroPostTypeArray) {
                return $heroPostType->id === $heroPostTypeArray['id'];
            });

            $this->assertGreaterThan(10000, $heroPostTypeArray['recruitmentCost']);

            $recruitmentCost = $heroPostType->getRecruitmentCost($squad);
            $this->assertEquals($recruitmentCost, $heroPostTypeArray['recruitmentCost']);
        }
    }
}
