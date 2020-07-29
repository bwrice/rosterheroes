<?php


namespace App\Domain\Actions;


use App\Domain\DataTransferObjects\GameDTO;
use App\Domain\Models\Game;
use App\Domain\Models\StatsIntegrationType;
use App\Domain\Models\Week;
use App\External\Stats\StatsIntegration;
use App\Facades\CurrentWeek;
use App\Jobs\CreateSpiritsForGameJob;
use App\Jobs\DisableSpiritsForGameJob;

class UpdateSingleGame
{
    /**
     * @var StatsIntegration
     */
    protected $statsIntegration;
    /**
     * @var DisableSpiritsForGame
     */
    protected $disableSpiritsForGame;

    public function __construct(StatsIntegration $statsIntegration, DisableSpiritsForGame $disableSpiritsForGame)
    {
        $this->statsIntegration = $statsIntegration;
        $this->disableSpiritsForGame = $disableSpiritsForGame;
    }

    public function execute(GameDTO $gameDTO)
    {
        $integrationType = $this->statsIntegration->getIntegrationType();
        $game = Game::query()->forIntegration($integrationType->id, $gameDTO->getExternalID())->first();

        if ($game) {

            $this->updateGame($game, $gameDTO);

        } else {
            /** @var Game $game */
            $game = Game::query()->create([
                'starts_at' => $gameDTO->getStartsAt(),
                'home_team_id' => $gameDTO->getHomeTeam()->id,
                'away_team_id' => $gameDTO->getAwayTeam()->id,
            ]);

            $game->externalGames()->create([
                'integration_type_id' => $integrationType->id,
                'external_id' => $gameDTO->getExternalID()
            ]);

            $validPeriodForWeek = CurrentWeek::validGamePeriod();
            if ($game->starts_at->isBetween($validPeriodForWeek->getStartDate(), $validPeriodForWeek->getEndDate())) {
                CreateSpiritsForGameJob::dispatch($game, CurrentWeek::get());
            }
        }
    }

    protected function updateGame(Game $game, GameDTO $gameDTO)
    {
        $disableSpirits = false;

        if ($game->starts_at->timestamp !== $gameDTO->getStartsAt()->timestamp) {
            if ($this->disableSpiritsBasedOffGameTime($game, $gameDTO)) {
                $disableSpirits = true;
            }
            $game->starts_at = $gameDTO->getStartsAt();
            $game->save();
        }

        if ($game->schedule_status !== $gameDTO->getStatus()) {
            if ($this->disableSpiritsBasedOffStatus($game, $gameDTO)) {
                $disableSpirits = true;
            }
            $game->schedule_status = $gameDTO->getStatus();
            $game->save();
        }

        if ($disableSpirits) {
            DisableSpiritsForGameJob::dispatch($game);
        }

        return $game;
    }

    /**
     * @param Game $game
     * @param GameDTO $gameDTO
     * @return bool
     */
    protected function disableSpiritsBasedOffGameTime(Game $game, GameDTO $gameDTO)
    {
        $validPeriod = CurrentWeek::validGamePeriod();

        // If original game time wasn't valid for week, we don't need to worry about disabling spirits
        if (! $game->starts_at->isBetween($validPeriod->getStartDate(), $validPeriod->getEndDate())) {
            return false;
        }

        return ! $gameDTO->getStartsAt()->isBetween($validPeriod->getStartDate(), $validPeriod->getEndDate());
    }

    protected function disableSpiritsBasedOffStatus(Game $game, GameDTO $gameDTO)
    {
        // If original status wasn't valid for spirits, we don't need to worry about disabling spirits
        if (! in_array($game->schedule_status, [
            Game::SCHEDULE_STATUS_NORMAL,
            Game::SCHEDULE_STATUS_DELAYED
        ])) {
            return false;
        }

        return in_array($gameDTO->getStatus(), [
            Game::SCHEDULE_STATUS_POSTPONED,
            Game::SCHEDULE_STATUS_CANCELED
        ]);
    }

}
