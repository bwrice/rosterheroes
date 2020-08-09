<?php


namespace App\Domain\Actions;


use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\PlayerStat;
use App\Exceptions\DeleteGameException;
use Illuminate\Database\Eloquent\Builder;

class DeleteGame
{
    /**
     * @var DisableSpiritsForGame
     */
    protected $disableSpiritsForGame;

    public function __construct(DisableSpiritsForGame $disableSpiritsForGame)
    {
        $this->disableSpiritsForGame = $disableSpiritsForGame;
    }

    /**
     * @param Game $game
     * @return bool|null
     * @throws DeleteGameException
     */
    public function execute(Game $game)
    {
        $playerSpiritsCount = PlayerSpirit::query()->whereHas('playerGameLog', function (Builder $builder) use ($game) {
            $builder->where('game_id', '=', $game->id);
        })->count();

        if ($playerSpiritsCount) {
            $this->disableSpiritsForGame->execute($game);
        }

        $gameLogs = $game->playerGameLogs()->whereHas('playerStats')->get();

        if ($gameLogs->isNotEmpty()) {
            $message = "Game (" . $game->id . "): " . $game->getSimpleDescription()
                . " has " . $gameLogs->count() . " game logs with stats";
            throw new DeleteGameException($game, $message, DeleteGameException::CODE_GAME_HAS_STATS);
        }
        $game->playerGameLogs()->delete();
        return $game->delete();
    }
}
