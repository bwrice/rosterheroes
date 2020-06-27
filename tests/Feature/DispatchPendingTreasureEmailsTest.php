<?php

namespace Tests\Feature;

use App\Domain\Actions\DispatchPendingTreasureEmails;
use App\Domain\Models\ChestBlueprint;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\SquadFactory;
use App\Mail\TreasuresPending;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DispatchPendingTreasureEmailsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @param int $weeksBack
     * @return DispatchPendingTreasureEmails
     */
    protected function getDomainAction(int $weeksBack = 1)
    {
        /** @var DispatchPendingTreasureEmails $domainAction */
        $domainAction = app(DispatchPendingTreasureEmails::class);
        return $domainAction->setWeeksBack($weeksBack);
    }

    /**
     * @test
     */
    public function it_will_not_queue_emails_to_squads_with_a_recent_opened_chest()
    {
        Mail::fake();
        $chest = ChestFactory::new()->opened()->create();
        $squad = $chest->squad;

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(TreasuresPending::class, function (TreasuresPending $mail) use ($squad) {
            return $mail->squad->id === $squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_queue_emails_to_squads_with_a_non_recent_unopened_chest()
    {
        Mail::fake();
        $chest = ChestFactory::new()->create([
            'created_at' => now()->subMonths(2)
        ]);
        $squad = $chest->squad;

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(TreasuresPending::class, function (TreasuresPending $mail) use ($squad) {
            return $mail->squad->id === $squad->id;
        });
    }

    /**
     * @test
     */
    public function it_will_queue_emails_to_squads_with_a_recent_unopened_chest()
    {
        Mail::fake();
        $squad = SquadFactory::new()->create();
        $chestsCount = rand(2, 5);
        $chestFactory = ChestFactory::new()->withSquadID($squad->id);
        for ($i = 1; $i <= $chestsCount; $i++) {
            $chestFactory->create();
        }

//        dd($squad->unopenedChests->toArray());

        $this->getDomainAction()->execute();

        Mail::assertQueued(TreasuresPending::class, function (TreasuresPending $mail) use ($squad, $chestsCount) {
            return $mail->squad->id === $squad->id && $mail->unopenedChestsCount === $chestsCount;
        });
    }

    /**
     * @test
     */
    public function it_will_not_queue_emails_for_squads_with_only_the_newcomer_chest_unopened()
    {
        Mail::fake();
        $newcomerBlueprint = ChestBlueprint::query()
            ->where('reference_id', '=', ChestBlueprint::NEWCOMER_CHEST)
            ->first();

        $chest = ChestFactory::new()->withChestBlueprintID($newcomerBlueprint->id)->create();
        $squad = $chest->squad;

        $this->getDomainAction()->execute();

        Mail::assertNotQueued(TreasuresPending::class, function (TreasuresPending $mail) use ($squad) {
            return $mail->squad->id === $squad->id;
        });
    }
}
