<?php

namespace Tests\Unit;

use App\Province;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvinceTest extends TestCase
{
    /**
     * @test
     */
    public function province_borders_and_border_by_are_the_same()
    {
        $provinces = Province::with('borders', 'borderedBy')->get();

        $provinces->each(function(Province $province) {
           $borderIDs = $province->borders->pluck('id')->toArray();
           $borderByIDs = $province->borderedBy->pluck('id')->toArray();
           $this->assertEquals( $borderIDs, $borderByIDs, $province->name );
        });
    }

    /**
     * @test
     */
    public function it_will_return_a_starting_province()
    {
        for($i = 1; $i <= 40; $i++) {
            $province = Province::getStarting();
            $this->assertNotNull($province);
        }
    }

}
