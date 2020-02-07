<?php


namespace App\Factories\Models;


use App\Domain\Models\Player;
use App\Domain\Models\Team;
use Faker\Generator;

class PlayerFactory
{
    /** @var int */
    protected $teamID;
    /**
     * @var Generator
     */
    protected $faker;

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
            'team_id' => $this->teamID ?: $this->getTeam()->id,
            'first_name' => $this->faker->firstNameMale,
            'last_name' => $this->faker->lastName
        ], $extra));

        return $player;
    }

    /**
     * @return Team
     */
    protected function getTeam()
    {
        return factory(Team::class)->create();
    }

}
