<?php


namespace App\Factories\Models;


use App\Domain\Models\SideQuestResult;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
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

    /** @var CarbonInterface|null */
    protected $sideEffectsProcessedAt = null;

    protected $campaignStopID;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): SideQuestResult
    {
        /** @var SideQuestResult $sideQuestResult */
        $sideQuestResult = SideQuestResult::query()->create(array_merge([
            'uuid' => Str::uuid()->toString(),
            'campaign_stop_id' => $this->getCampaignStopID(),
            'side_quest_id' => $this->getSideQuest()->id,
            'combat_processed_at' => $this->combatProcessedAt,
            'rewards_processed_at' => $this->rewardsProcessedAt,
            'side_effects_processed_at' => $this->sideEffectsProcessedAt,
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
        $processedAt = $processedAt ?: Date::now();
        $clone = clone $this;
        $clone->combatProcessedAt = $processedAt;
        return $clone;
    }

    public function rewardsProcessed(CarbonInterface $processedAt = null)
    {
        $processedAt = $processedAt ?: Date::now();
        $clone = clone $this;
        $clone->rewardsProcessedAt = $processedAt;
        return $clone;
    }

    public function sideEffectsProcessed(CarbonInterface $processedAt = null)
    {
        $processedAt = $processedAt ?: Date::now();
        $clone = clone $this;
        $clone->sideEffectsProcessedAt = $processedAt;
        return $clone;
    }

    public function withCampaignStopID(int $campaignStopID)
    {
        $clone = clone $this;
        $clone->campaignStopID = $campaignStopID;
        return $clone;
    }

    protected function getCampaignStopID()
    {
        if ($this->campaignStopID) {
            return $this->campaignStopID;
        }
        return $this->getCampaignStop()->id;
    }
}
