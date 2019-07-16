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

class CreateNewSquadAction
{
    /**
     * @var string
     */
    private $uuid;
    /**
     * @var int
     */
    private $userID;
    /**
     * @var string
     */
    private $name;
    /**
     * @var UpdateSquadSlotsAction
     */
    private $updateSquadSlotsAction;

    public function __construct(
        string $uuid,
        int $userID,
        string $name,
        UpdateSquadSlotsAction $updateSquadSlotsAction)
    {
        $this->userID = $userID;
        $this->name = $name;
        $this->updateSquadSlotsAction = $updateSquadSlotsAction;
        $this->uuid = $uuid;
    }

    public function __invoke(): Squad
    {
        /** @var SquadAggregate $aggregate */
        $aggregate = SquadAggregate::retrieve($this->uuid);

        $aggregate->createSquad(
            $this->userID,
            $this->name,
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
        ($this->updateSquadSlotsAction)();

        return Squad::uuid($this->uuid);
    }
}