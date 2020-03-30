<?php


namespace App\Factories\Models;


use App\SideQuestResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SideQuestResultFactory
{
    /** @var SideQuestFactory|null */
    protected $sideQuestFactory;

    /** @var CampaignStopFactory|null */
    protected $campaignStopFactory;

    /** @var Collection|null */
    protected $sideQuestEventFactories;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): SideQuestResult
    {
        /** @var SideQuestResult $sideQuestResult */
        $sideQuestResult = SideQuestResult::query()->create(array_merge([
            'uuid' => Str::uuid()->toString(),
            'campaign_stop_id' => $this->getCampaignStop()->id,
            'side_quest_id' => $this->getSideQuest()->id
        ], $extra));

        if ($this->sideQuestEventFactories) {
            $this->sideQuestEventFactories->each(function (SideQuestEventFactory $sideQuestEventFactory) use ($sideQuestResult) {
                $sideQuestEventFactory->withSideQuestResultID($sideQuestResult->id)->create();
            });
        }

        return $sideQuestResult;
    }

    public function withSideQuest(SideQuestFactory $sideQuestFactory)
    {
        $clone = clone $this;
        $clone->sideQuestFactory = $sideQuestFactory;
        return $clone;
    }

    public function withCampaignStop(CampaignStopFactory $campaignStopFactory)
    {
        $clone = clone $this;
        $clone->campaignStopFactory = $campaignStopFactory;
        return $clone;
    }

    protected function getSideQuest()
    {
        $sideQuestFactory = $this->sideQuestFactory ?: SideQuestFactory::new();
        return $sideQuestFactory->create();
    }

    protected function getCampaignStop()
    {
        $campaignStopFactory = $this->campaignStopFactory ?: CampaignStopFactory::new();
        return $campaignStopFactory->create();
    }

    public function withEvents(Collection $sideQuestEventFactories)
    {
        $clone = clone $this;
        $clone->sideQuestEventFactories = $sideQuestEventFactories;
        return $clone;
    }
}
