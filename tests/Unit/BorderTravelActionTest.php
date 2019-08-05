<?php

namespace Tests\Unit;

use App\Domain\Actions\BorderTravelAction;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Exceptions\NotBorderedByException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorderTravelActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_they_dont_border_each_other()
    {
        /** @var Squad $squad */
        $squad = factory(Squad::class)->create();
        $currentProvince = $squad->province;

        /** @var Province $nonBorder */
        $nonBorder = Province::query()->whereDoesntHave('borders', function(Builder $builder) use ($currentProvince) {
            return $builder->where('id', '=', $currentProvince->id);
        })->first();

        /** @var BorderTravelAction $domainAction */
        $domainAction = app(BorderTravelAction::class);

        try {

            $domainAction->execute($squad, $nonBorder);

        } catch (NotBorderedByException $exception) {

            $squad = $squad->fresh();

            $this->assertEquals($currentProvince->id, $squad->province_id);
            return;
        }

        $this->fail("Exception not thrown");
    }
}
