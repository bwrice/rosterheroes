<?php

namespace Tests\Feature;

use App\Domain\Actions\MakeWeekCurrent;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MakeWeekCurrentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return MakeWeekCurrent
     */
    protected function getDomainAction()
    {
        return app(MakeWeekCurrent::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_week_is_already_has_made_current_set()
    {
        $madeCurrentAt = now()->subHours(4);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        $week->made_current_at = $madeCurrentAt;
        $week->save();

        try {
            $this->getDomainAction()->execute($week);
        } catch (\Exception $exception) {
            $this->assertEquals($week->fresh()->made_current_at->timestamp, $madeCurrentAt->timestamp);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_the_weeks_made_current_at_field_to_now()
    {
        $pastByOneSecond = now()->subSecond();

        /** @var Week $week */
        $week = factory(Week::class)->create();
        $this->getDomainAction()->execute($week);

        $week = $week->fresh();

        $madeCurrentAt = $week->made_current_at;
        $this->assertNotNull($madeCurrentAt);
        $this->assertGreaterThan($pastByOneSecond->timestamp, $madeCurrentAt->timestamp);
        $this->assertLessThan($pastByOneSecond->addMinute()->timestamp, $madeCurrentAt->timestamp);
    }
}
