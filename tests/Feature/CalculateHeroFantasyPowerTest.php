<?php

namespace Tests\Feature;

use App\Domain\Actions\CalculateHeroFantasyPower;
use App\Exceptions\CalculateHeroFantasyPowerException;
use App\Factories\Models\HeroFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateHeroFantasyPowerTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_player_spirit_for_hero()
    {
        $hero = HeroFactory::new()->create();
        $this->assertNull($hero->playerSpirit);

        try {
            /** @var CalculateHeroFantasyPower $domainAction */
            $domainAction = app(CalculateHeroFantasyPower::class);
            $domainAction->execute($hero);

        } catch (CalculateHeroFantasyPowerException $exception) {
            $this->assertEquals(CalculateHeroFantasyPowerException::CODE_NO_PLAYER_SPIRIT, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
