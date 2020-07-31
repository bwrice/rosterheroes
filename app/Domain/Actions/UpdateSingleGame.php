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
                'schedule_status' => $gameDTO->getStatus()
            ]);

            $game->externalGames()->create([
                'integration_type_id' => $integrationType->id,
                'external_id' => $gameDTO->getExternalID()
            ]);

            if ($this->gameShouldHaveSpirits($game)) {
                CreateSpiritsForGameJob::dispatch($game, CurrentWeek::get());
            }
        }
    }

    protected function updateGame(Game $game, GameDTO $gameDTO)
    {
        $originalGameShouldHaveSpirits = $this->gameShouldHaveSpirits($game);
        $disableSpiritsReason = null;

        if ($game->starts_at->timestamp !== $gameDTO->getStartsAt()->timestamp) {

            $game->starts_at = $gameDTO->getStartsAt();
            $game->save();

            // If original game had spirits and now it shouldn't, we need to disable
            if ($originalGameShouldHaveSpirits && $this->disableSpiritsBasedOffGameTime($game)) {
                $disableSpiritsReason = 'Game time no longer valid';
            }
        }

        if ($game->schedule_status !== $gameDTO->getStatus()) {

            $game->schedule_status = $gameDTO->getStatus();
            $game->save();

            // If original game had spirits and now it shouldn't, we need to disable
            if ($originalGameShouldHaveSpirits && $this->disableSpiritsBasedOffStatus($game)) {
                $disableSpiritsReason = 'Status changed to ' . $gameDTO->getStatus();
            }
        }

        if ($disableSpiritsReason) {
            DisableSpiritsForGameJob::dispatch($game, $disableSpiritsReason);
        }

        // Only if the original game did NOT already have spirits and it now should, do we need to create spirits
        if (!$originalGameShouldHaveSpirits && $this->gameShouldHaveSpirits($game)) {
            CreateSpiritsForGameJob::dispatch($game, CurrentWeek::get());
        }

        return $game;
    }

    /**
     * @param Game $game
     * @return bool
     */
    protected function disableSpiritsBasedOffGameTime(Game $game)
    {
        $validPeriod = CurrentWeek::validGamePeriod();
        return ! $game->starts_at->isBetween($validPeriod->getStartDate(), $validPeriod->getEndDate());
    }

    /**
     * @param Game $game
     * @return bool
     */
    protected function disableSpiritsBasedOffStatus(Game $game)
    {
        return in_array($game->schedule_status, [
            Game::SCHEDULE_STATUS_POSTPONED,
            Game::SCHEDULE_STATUS_CANCELED
        ]);
    }

    protected function gameShouldHaveSpirits(Game $game)
    {
        if (!in_array($game->schedule_status, [
            Game::SCHEDULE_STATUS_NORMAL,
            Game::SCHEDULE_STATUS_DELAYED
        ])) {
            return false;
        }
        $validPeriodForWeek = CurrentWeek::validGamePeriod();

        return $game->starts_at->isBetween($validPeriodForWeek->getStartDate(), $validPeriodForWeek->getEndDate());
    }

}
