<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/18/19
 * Time: 2:22 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\CampaignAggregate;
use App\Domain\Models\Campaign;
use App\Domain\Models\Continent;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignExistsException;
use App\Exceptions\WeekLockedException;
use Illuminate\Support\Str;

class CreateCampaignAction
{
    /**
     * @var Squad
     */
    private $squad;
    /**
     * @var Week
     */
    private $week;
    /**
     * @var Continent
     */
    private $continent;

    public function __construct(Squad $squad, Week $week, Continent $continent)
    {
        $this->squad = $squad;
        $this->week = $week;
        $this->continent = $continent;
    }

    /**
     * @return Campaign
     * @throws CampaignExistsException
     * @throws WeekLockedException
     */
    public function __invoke(): Campaign
    {
        /** @var Campaign $campaignForWeek */
        $campaignForWeek = Campaign::forSquadWeek($this->squad, $this->week)->first();
        if ($campaignForWeek) {
            throw new CampaignExistsException($campaignForWeek);
        } elseif (! $this->week->adventuringOpen()) {
            throw new WeekLockedException($this->week);
        }

        $campaignUuid = Str::uuid();
        /** @var CampaignAggregate $campaignAggregate */
        $campaignAggregate = CampaignAggregate::retrieve($campaignUuid);
        $campaignAggregate->createCampaign($this->squad->id, $this->week->id, $this->continent->id)->persist();

        return Campaign::uuid($campaignUuid);
    }
}