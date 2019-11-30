<?php

namespace Tests\Unit;

use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetupNextWeekActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $previousWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->previousWeek = factory(Week::class)->create();

    }

}
