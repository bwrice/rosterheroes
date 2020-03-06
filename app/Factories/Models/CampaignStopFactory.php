<?php


namespace App\Factories\Models;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Province;
use Illuminate\Support\Str;

class CampaignStopFactory
{
    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'campaign_id' => CampaignFactory::new()->create()->id,
            'quest_id' => QuestFactory::new()->create()->id,
            'province_id' => Province::query()->inRandomOrder()->first()->id
        ], $extra));
        return $campaignStop;
    }

}
