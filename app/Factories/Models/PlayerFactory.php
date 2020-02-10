<?php


namespace App\Factories\Models;


use App\Domain\Models\Player;
use App\Domain\Models\Position;
use App\Domain\Models\Team;
use Faker\Generator;

class PlayerFactory
{
    /** @var Team */
    protected $team;
    /**
     * @var Generator
     */
    protected $faker;

    /** @var bool  */
    protected $withPosition = false;

    /** @var Position|null */
    protected $position;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public static function new(): self
    {
        return app(self::class);
    }

    public function create(array $extra = [])
    {
        /** @var Player $player */
        $player = Player::query()->create(array_merge([
            'team_id' => $this->getTeam()->id,
            'first_name' => $this->faker->firstNameMale,
            'last_name' => $this->faker->lastName
        ], $extra));

        if ($this->withPosition) {
            $position = $this->getPosition($player);
            $player->positions()->save($position);
        }

        return $player->fresh();
    }

    /**
     * @return Team
     */
    protected function getTeam()
    {
        if ($this->team) {
            return $this->team;
        }
        return factory(Team::class)->create();
    }

    public function forTeam(Team $team)
    {
        $clone = clone $this;
        $clone->team = $team;
        return $clone;
    }

    public function withPosition(Position $position = null)
    {
        $clone = clone $this;
        $clone->withPosition = true;
        $clone->position = $position;
        return $clone;
    }

    protected function getPosition(Player $player)
    {
        if ($this->position) {
            return $this->position;
        }
        return $player->team->league->sport->positions()->inRandomOrder()->first();
    }

}
