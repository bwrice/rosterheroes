<?php


namespace App\Factories\Models;


use App\SideQuestResult;
use Carbon\CarbonInterface;
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

    /** @var CarbonInterface|null */
    protected $combatProcessedAt = null;

    /** @var CarbonInterface|null */
    protected $rewardsProcessedAt = null;

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
            'side_quest_id' => $this->getSideQuest()->id,
            'combat_processed_at' => $this->combatProcessedAt,
            'rewards_processed_at' => $this->rewardsProcessedAt,
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

    public function combatProcessed(CarbonInterface $processedAt = null)
    {
        $clone = clone $this;
        $clone->combatProcessedAt = $processedAt;
        return $clone;
    }

    public function rewardsProcessed(CarbonInterface $processedAt = null)
    {
        $clone = clone $this;
        $clone->rewardsProcessedAt = $processedAt;
        return $clone;
    }
}
