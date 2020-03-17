<?php


namespace App\Factories\Models;


use App\Domain\Models\League;
use App\Domain\Models\Team;
use Illuminate\Database\Eloquent\Model;

class TeamFactory
{
    protected $leagueName;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var Team $team */
        $team = Team::query()->create(array_merge([
            'league_id' => $this->getLeague()->id,
            'name' => 'Test Team',
            'location' => 'Anywhere',
            'abbreviation' => 'TTA'
        ], $extra));
        return $team;
    }

    /**
     * @return Model|League
     */
    protected function getLeague()
    {
        if ($this->leagueName) {
            return League::query()->where('name', '=', $this->leagueName)->first();
        }
        return League::query()->inRandomOrder()->first();
    }

    public function forLeague(string $leagueName)
    {
        $clone = clone $this;
        $clone->leagueName = $leagueName;
        return $clone;
    }
}
