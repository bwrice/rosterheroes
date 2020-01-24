<?php

namespace Tests\Feature;

use App\Domain\Actions\Testing\AutoAttachSpiritToHeroAction;
use App\Domain\Actions\Testing\AutoManageHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutoManageHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    public function setUp(): void
    {
        parent::setUp();
        $this->hero = factory(Hero::class)->create();
    }

    /**
    * @test
    */
    public function it_will_execute_attach_spirit_action()
    {
        $spy = \Mockery::spy(AutoAttachSpiritToHeroAction::class);
        app()->instance(AutoAttachSpiritToHeroAction::class, $spy);

        /** @var AutoManageHeroAction $domainAction */
        $domainAction = app(AutoManageHeroAction::class);
        $domainAction->execute($this->hero);
        $spy->shouldHaveReceived('execute');
    }
}
