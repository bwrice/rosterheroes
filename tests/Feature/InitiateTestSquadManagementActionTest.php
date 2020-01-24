<?php

namespace Tests\Feature;

use App\Domain\Actions\Testing\InitiateTestSquadManagementAction;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use App\Jobs\AutoManageSquadJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InitiateTestSquadManagementActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Squad
     */
    protected $squadOne;

    /**
     * @var Squad
     */
    protected $squadTwo;

    /**
     * @var Squad
     */
    protected $squadThree;

    /** @var InitiateTestSquadManagementAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $testID = random_int(10000,99999);

        // Valid
        $this->squadOne = factory(Squad::class)->create([
            'name' => 'TestSquad' . $testID,
            'user_id' => factory(User::class)->create([
                'name' => 'TestUser' . $testID,
                'email' => 'testUser' . $testID . '@test.com'
            ])->id
        ]);

        $testID = random_int(10000,99999);

        // Valid Team but Invalid User
        $this->squadTwo = factory(Squad::class)->create([
            'name' => 'TestSquad' . $testID,
            'user_id' => factory(User::class)->create([
                'name' => 'TestUser' . $testID,
                'email' => 'actualEmail' . random_int(1,9999) . '@gmail.com'
            ])->id
        ]);

        // Invalid Team
        $this->squadThree = factory(Squad::class)->create([
            'name' => 'Cool Team Name ' . random_int(1,9999),
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->domainAction = app(InitiateTestSquadManagementAction::class);
    }

    /**
    * @test
    */
    public function it_will_queue_auto_manage_jobs_for_test_teams_only()
    {
        Queue::fake();

        $this->domainAction->execute();

        Queue::assertPushed(AutoManageSquadJob::class, function (AutoManageSquadJob $job) {
            return $job->squad->id === $this->squadOne->id;
        });

        Queue::assertNotPushed(AutoManageSquadJob::class, function (AutoManageSquadJob $job) {
            return $job->squad->id === $this->squadTwo->id;
        });

        Queue::assertNotPushed(AutoManageSquadJob::class, function (AutoManageSquadJob $job) {
            return $job->squad->id === $this->squadThree->id;
        });
    }
}
