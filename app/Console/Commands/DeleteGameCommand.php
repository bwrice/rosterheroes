<?php

namespace App\Console\Commands;

use App\Domain\Actions\DeleteGame;
use App\Domain\Models\Game;
use App\Domain\Models\PlayerSpirit;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class DeleteGameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rh:delete-game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a game by it\'s id';

    /**
     * @param DeleteGame $deleteGame
     * @return bool|null
     * @throws \App\Exceptions\DeleteGameException
     */
    public function handle(DeleteGame $deleteGame)
    {
        $gameID = $this->ask('Game ID?');

        /** @var Game $game */
        $game = Game::query()->find($gameID);

        if (! $game) {
            $this->error('No game found with ID: ' . $gameID);
            return false;
        }

        if (! $this->confirm('Delete game: ' . $game->getSimpleDescription() . ' ?')) {
            return false;
        }

        $spiritQuery = PlayerSpirit::query()->whereHas('playerGameLog', function (Builder $builder) use ($gameID) {
            $builder->where('game_id', '=', $gameID);
        });
        $spiritsCount = (clone $spiritQuery)->count();

        if ($spiritsCount > 0) {

            $heroesCount = $spiritQuery->withCount('heroes')->get()->sum(function (PlayerSpirit $playerSpirit) {
                return $playerSpirit->heroes_count;
            });

            if (! $this->confirm($spiritsCount . ' spirits with ' . $heroesCount . ' heroes found. Continue?')) {
                return false;
            }
        }

        $result = $deleteGame->execute($game);
        $this->info('Game with ID: ' . $game->id . ' deleted');
        return $result;
    }
}
