<?php


namespace App\Factories\Models;


use App\Domain\Models\Player;
use App\Domain\Models\Position;
use App\Domain\Models\Team;
use Faker\Generator;

class PlayerFactory
{
    /** @var int */
    protected $teamID;

    /** @var TeamFactory|null */
    protected $teamFactory;

    /**
     * @var Generator
     */
    protected $faker;

    /** @var bool  */
    protected $withPosition = false;

    /** @var Position|null */
    protected $position;

    protected $status = Player::STATUS_ROSTER;

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
            'team_id' => $this->getTeamID(),
            'first_name' => $this->faker->firstNameMale,
            'last_name' => $this->faker->lastName,
            'status' => $this->status
        ], $extra));

        if ($this->withPosition) {
            $position = $this->getPosition($player);
            $player->positions()->save($position);
        }

        return $player->fresh();
    }

    /**
     * @return int
     */
    protected function getTeamID()
    {
        if ($this->teamID) {
            return $this->teamID;
        }
        if ($this->teamFactory) {
            return $this->teamFactory->create()->id;
        }
        return TeamFactory::new()->create()->id;
    }

    public function forTeam(TeamFactory $teamFactory)
    {
        $clone = clone $this;
        $clone->teamFactory = $teamFactory;
        return $clone;
    }

    public function withTeamID(int $teamID)
    {
        $clone = clone $this;
        $clone->teamID = $teamID;
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

    public function retired()
    {
        $clone = clone $this;
        $clone->status = Player::STATUS_RETIRED;
        return $clone;
    }

}
