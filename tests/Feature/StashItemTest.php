<?php

namespace Tests\Feature;

use App\Domain\Actions\StashItem;
use App\Domain\Models\Province;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ResidenceFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestsItemsTransactions;

class StashItemTest extends TestCase
{
    use TestsItemsTransactions;
    use DatabaseTransactions;

    /**
     * @return StashItem
     */
    protected function getDomainAction()
    {
        return app(StashItem::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_item_does_not_belong_to_the_squad()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $diffSquad = SquadFactory::new()->create();
        $diffSquad->items()->save($item);

        try {
            $this->getDomainAction()->execute($item, $squad);
        } catch (\Exception $exception) {
            $this->assertTrue($item->ownedByMorphable($diffSquad));
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_move_the_item_to_the_stash_at_the_squads_current_location()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->items()->save($item);
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();

        $item = $this->getDomainAction()->execute($item, $squad);

        $this->assertTrue($item->ownedByMorphable($stash));
        $this->assertItemTransactionMatches($item, $stash, $squad);
    }

    /**
     * @test
     */
    public function it_will_stash_the_item_even_if_a_local_residence_with_space_exists()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->items()->save($item);
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $localResidence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $residenceItemsCount = $localResidence->items()->count();

        $item = $this->getDomainAction()->execute($item, $squad);

        $this->assertTrue($item->ownedByMorphable($stash));
        $this->assertItemTransactionMatches($item, $stash, $squad);
        $this->assertEquals($residenceItemsCount, $localResidence->items()->count());
    }

    /**
     * @test
     */
    public function it_will_stash_an_item_from_a_local_residence()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $residence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $residence->items()->save($item);
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();

        $item = $this->getDomainAction()->execute($item, $squad);

        $this->assertTrue($item->ownedByMorphable($stash));
        $this->assertItemTransactionMatches($item, $stash, $residence);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_when_trying_to_stash_an_item_in_a_non_local_residence()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $diffProvince = Province::query()->where('id', '!=', $squad->province_id)->inRandomOrder()->first();
        $nonLocalResidence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($diffProvince)->create();
        $nonLocalResidence->items()->save($item);

        try {
            $this->getDomainAction()->execute($item, $squad);
        } catch (\Exception $exception) {
            $this->assertTrue($item->ownedByMorphable($nonLocalResidence));
            return;
        }
        $this->fail("Exception not thrown");
    }
}
