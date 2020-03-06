<?php


namespace App\Factories\Models;


use App\SideQuestResult;
use Illuminate\Support\Str;

class SideQuestResultFactory
{
    /** @var SideQuestFactory|null */
    protected $sideQuestFactory;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): SideQuestResult
    {
        /** @var SideQuestResult $sideQuestResult */
        $sideQuestResult = SideQuestResult::query()->create(array_merge([
            'uuid' => Str::uuid()->toString(),
            'campaign_stop_id' => CampaignStopFactory::new()->create()->id,
            'side_quest_id' => $this->getSideQuest()->id
        ], $extra));

        return $sideQuestResult;
    }

    public function withSideQuest(SideQuestFactory $sideQuestFactory)
    {
        $clone = clone $this;
        $clone->sideQuestFactory = $sideQuestFactory;
        return $clone;
    }

    protected function getSideQuest()
    {
        $sideQuestFactory = $this->sideQuestFactory ?: SideQuestFactory::new();
        return $sideQuestFactory->create();
    }
}
