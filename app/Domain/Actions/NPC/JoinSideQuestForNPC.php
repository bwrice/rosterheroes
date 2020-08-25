<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Facades\CurrentWeek;
use Illuminate\Database\Eloquent\Builder;

class JoinSideQuestForNPC extends NPCAction
{
    public const EXCEPTION_NO_VALID_CAMPAIGN_STOP = 7;

    /**
     * @var JoinSideQuestAction
     */
    protected $joinSideQuestAction;

    public function __construct(JoinSideQuestAction $joinSideQuestAction)
    {
        $this->joinSideQuestAction = $joinSideQuestAction;
    }

    public function handleExecute(SideQuest $sideQuest)
    {
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::query()->whereHas('campaign', function (Builder $builder) {
            $builder->where('squad_id', '=', $this->npc->id)
                ->where('week_id', '=', CurrentWeek::id());
        })->where('quest_id', '=', $sideQuest->quest_id)->first();

        if (is_null($campaignStop)) {
            throw new \Exception("No valid campaign stop found for NPC: " . $this->npc->name, self::EXCEPTION_NO_VALID_CAMPAIGN_STOP);
        }

        $this->joinSideQuestAction->execute($campaignStop, $sideQuest);
    }
}
