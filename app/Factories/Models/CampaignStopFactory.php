<?php


namespace App\Factories\Models;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Province;
use Illuminate\Support\Str;

class CampaignStopFactory
{
    /** @var CampaignFactory|null */
    protected $campaignFactory;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'campaign_id' => $this->getCampaignID(),
            'quest_id' => QuestFactory::new()->create()->id,
            'province_id' => Province::query()->inRandomOrder()->first()->id
        ], $extra));
        return $campaignStop;
    }

    protected function getCampaignID()
    {
        if ($this->campaignFactory) {
            return $this->campaignFactory->create()->id;
        }
        return CampaignFactory::new()->create()->id;
    }

    public function withCampaign(CampaignFactory $campaignFactory)
    {
        $clone = clone $this;
        $clone->campaignFactory = $campaignFactory;
        return $clone;
    }

}
