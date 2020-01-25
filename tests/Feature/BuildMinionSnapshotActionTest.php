<?php

namespace Tests\Feature;

use App\Domain\Models\Minion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildMinionSnapshotActionTest extends TestCase
{
    /** @var Minion */
    protected $minion;

    public function setUp(): void
    {
        parent::setUp();

        $this;
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
