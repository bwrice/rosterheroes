<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/15/19
 * Time: 9:13 AM
 */

namespace App\Domain\Actions;


use App\Aggregates\SquadAggregate;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use Illuminate\Support\Str;

class CreateSquadAction
{
    /**
     * @var UpdateSquadSlotsAction
     */
    private $updateSquadSlotsAction;

    public function __construct(UpdateSquadSlotsAction $updateSquadSlotsAction)
    {
        $this->updateSquadSlotsAction = $updateSquadSlotsAction;
    }

    /**
     * @param int $userID
     * @param string $name
     * @return Squad
     */
    public function execute(int $userID, string $name): Squad
    {
        $squadUuid = Str::uuid();
        /** @var SquadAggregate $aggregate */
        $aggregate = SquadAggregate::retrieve($squadUuid);

        $aggregate->createSquad(
            $userID,
            $name,
            SquadRank::getStarting()->id,
            MobileStorageRank::getStarting()->id,
            Province::getStarting()->id
        )->increaseEssence(Squad::STARTING_ESSENCE)
            ->increaseGold(Squad::STARTING_GOLD)
            ->increaseFavor(Squad::STARTING_FAVOR);

        $startingHeroPostTypes = HeroPostType::squadStarting();
        $startingHeroPostTypes->each(function (array $startingHeroPostType) use ($aggregate) {
            foreach (range(1, $startingHeroPostType['count']) as $count) {
                $aggregate->addHeroPost($startingHeroPostType['name']);
            }
        });

        /*
         * We need to persist before we can update the slots,
         * because that action will query the DB
         */
        $aggregate->persist();
        $squad = Squad::findUuid($squadUuid);
        $this->updateSquadSlotsAction->execute($squad);

        return $squad->fresh();
    }
}
