<?php


namespace App\Domain\Actions\NPC;



use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Chest;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Quest;
use App\Domain\Models\RecruitmentCamp;
use App\Domain\Models\Shop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Facades\Admin;
use App\Jobs\EmbodyNPCHeroJob;
use App\Jobs\EquipItemForHeroJob;
use App\Jobs\JoinQuestForNPCJob;
use App\Jobs\JoinSideQuestForNPCJob;
use App\Jobs\MoveNPCToProvinceJob;
use App\Jobs\OpenChestJob;
use App\Jobs\RaiseMeasurableJob;
use App\Jobs\RecruitHeroForNPCJob;
use App\Jobs\SellItemBundleForNPCJob;
use App\Notifications\NPCSelfManaged;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

/**
 * Class AutoManageNPC
 * @package App\Domain\Actions\NPC
 *
 * @method execute(Squad $squad, float $triggerChance, $actions = self::DEFAULT_ACTIONS)
 */
class AutoManageNPC extends NPCAction
{
    public const ACTION_OPEN_CHESTS = 'open-chests';
    public const ACTION_JOIN_QUESTS = 'join-quests';
    public const ACTION_EMBODY_HEROES = 'embody-heroes';
    public const ACTION_SELL_ITEMS = 'sell-items';
    public const ACTION_RECRUIT_HERO = 'recruit-hero';
    public const ACTION_RAISE_MEASURABLES = 'raise-measurables';
    public const ACTION_EQUIP_ITEMS = 'equip-items';

    public const DEFAULT_ACTIONS = [
        self::ACTION_OPEN_CHESTS,
        self::ACTION_JOIN_QUESTS,
        self::ACTION_EMBODY_HEROES,
        self::ACTION_SELL_ITEMS,
        self::ACTION_RECRUIT_HERO,
        self::ACTION_RAISE_MEASURABLES,
        self::ACTION_EQUIP_ITEMS
    ];

    public const ADVENTURING_LOCKED_ACTIONS = [
        self::ACTION_OPEN_CHESTS,
        self::ACTION_SELL_ITEMS
    ];

    protected FindChestsToOpen $findChestsToOpen;
    protected FindQuestsToJoin $findQuestsToJoin;
    protected FindSpiritsToEmbodyHeroes $findSpiritsToEmbodyHeroes;
    protected FindItemsToSell $findItemsToSell;
    protected FindHeroToRecruit $findHeroToRecruit;
    protected FindMeasurablesToRaise $findMeasurablesToRaise;
    protected FindItemsToEquip $findItemsToEquip;

    public function __construct(
        FindChestsToOpen $findChestsToOpen,
        FindQuestsToJoin $findQuestsToJoin,
        FindSpiritsToEmbodyHeroes $findSpiritsToEmbodyHeroes,
        FindItemsToSell $findItemsToSell,
        FindHeroToRecruit $findHeroToRecruit,
        FindMeasurablesToRaise $findMeasurablesToRaise,
        FindItemsToEquip $findItemsToEquip)
    {
        $this->findChestsToOpen = $findChestsToOpen;
        $this->findQuestsToJoin = $findQuestsToJoin;
        $this->findSpiritsToEmbodyHeroes = $findSpiritsToEmbodyHeroes;
        $this->findItemsToSell = $findItemsToSell;
        $this->findHeroToRecruit = $findHeroToRecruit;
        $this->findMeasurablesToRaise = $findMeasurablesToRaise;
        $this->findItemsToEquip = $findItemsToEquip;
    }

    /**
     * @param float $triggerChance
     * @param $actions
     * @throws \Exception
     */
    public function handleExecute(float $triggerChance, array $actions = null)
    {
        $actions = is_null($actions) ? self::DEFAULT_ACTIONS : $actions;
        $jobs = collect();
        $secondsDelay = 0;
        $now = now();

        $actions = collect($actions);
        $originalTriggerChance = $triggerChance;
        $actions->each(function ($action) use (&$jobs, &$secondsDelay, $now, &$triggerChance) {

            // Multiply both by 100 so we can compare out to two decimals
            if (rand(0, 100 * 100) <= $triggerChance * 100) {

                // If an action is triggered we bump the chances for further actions to be triggered
                $triggerChance += 25;

                $jobsToAdd = collect();
                $maxSecondsBetweenJobs = 10;
                switch ($action) {
                    case self::ACTION_OPEN_CHESTS:
                        $jobsToAdd = $this->getOpenChestJobs();
                        $maxSecondsBetweenJobs = 10;
                        break;
                    case self::ACTION_JOIN_QUESTS:
                        $jobsToAdd = $this->getJoinQuestJobs();
                        $maxSecondsBetweenJobs = 180;
                        break;
                    case self::ACTION_EMBODY_HEROES:
                        $jobsToAdd = $this->getEmbodyHeroJobs();
                        $maxSecondsBetweenJobs = 60;
                        break;
                    case self::ACTION_RAISE_MEASURABLES:
                        $jobsToAdd = $this->getRaiseMeasurableJobs();
                        $maxSecondsBetweenJobs = 30;
                        break;
                    case self::ACTION_EQUIP_ITEMS:
                        $jobsToAdd = $this->getEquipItemJobs();
                        $maxSecondsBetweenJobs = 10;
                        break;
                    case self::ACTION_SELL_ITEMS:
                        $jobsToAdd = $this->getItemsToSellJobs();
                        $maxSecondsBetweenJobs = 300;
                        break;
                    case self::ACTION_RECRUIT_HERO:
                        $jobsToAdd = $this->getRecruitHeroJobs();
                        $maxSecondsBetweenJobs = 180;
                        break;
                }

                $jobsToAdd->each(function ($job) use(&$secondsDelay, $now, $maxSecondsBetweenJobs) {
                    /** @var Queueable $job */
                    $secondsDelay += rand(1, $maxSecondsBetweenJobs);
                    $job->delay($now->addSeconds($secondsDelay));
                });
                $jobs = $jobs->merge($jobsToAdd);
            }
        });

        if ($jobs->isNotEmpty()) {
            Bus::chain($jobs->toArray())->dispatch();
            $this->notifyAdmin($jobs, $originalTriggerChance);
        }
    }

    protected function getOpenChestJobs()
    {
        $chests = $this->findChestsToOpen->execute($this->npc);
        return $chests->map(function (Chest $chest) {
            return new OpenChestJob($chest);
        });
    }

    protected function getJoinQuestJobs()
    {
        $questArrays = $this->findQuestsToJoin->execute($this->npc);
        $jobs = collect();
        $questArrays->each(function ($questArray) use ($jobs) {

            /** @var Quest $quest */
            $quest = $questArray['quest'];
            $jobs->push(new MoveNPCToProvinceJob($this->npc, $quest->province));
            $jobs->push(new JoinQuestForNPCJob($this->npc, $quest));

            /** @var Collection $sideQuests */
            $sideQuests = $questArray['side_quests'];
            $sideQuests->each(function (SideQuest $sideQuest) use ($jobs) {
                $jobs->push(new JoinSideQuestForNPCJob($this->npc, $sideQuest));
            });
        });
        return $jobs;
    }

    protected function getEmbodyHeroJobs()
    {
        $embodyHeroArrays = $this->findSpiritsToEmbodyHeroes->execute($this->npc);
        return $embodyHeroArrays->map(function ($embodyArray) {
            return new EmbodyNPCHeroJob($embodyArray['hero'], $embodyArray['player_spirit']);
        });
    }

    protected function getItemsToSellJobs()
    {
        $data = $this->findItemsToSell->execute($this->npc);
        if (! $data) {
            return collect();
        }
        /** @var ItemCollection $items */
        $items = $data['items'];
        /** @var Shop $shop */
        $shop = $data['shop'];
        return collect([
            new MoveNPCToProvinceJob($this->npc, $shop->province),
            new SellItemBundleForNPCJob($items, $this->npc, $shop)
        ]);
    }

    protected function notifyAdmin(Collection $jobs, float $triggerChance)
    {
        // Convert jobs into array of "job class name" => "job count"
        $jobGroups = $jobs->groupBy(function ($job) {
            return class_basename($job);
        })->map(function (Collection $groupedJobs) {
            return $groupedJobs->count();
        });
        Admin::notify(new NPCSelfManaged($this->npc, $triggerChance, $jobGroups->toArray()));
    }

    protected function getRecruitHeroJobs()
    {
        $data = $this->findHeroToRecruit->execute($this->npc);
        if (! $data) {
            return collect();
        }
        /** @var RecruitmentCamp $recruitmentCamp */
        $recruitmentCamp = $data['recruitment_camp'];
        return collect([
            new MoveNPCToProvinceJob($this->npc, $recruitmentCamp->province),
            new RecruitHeroForNPCJob(
                $this->npc,
                $recruitmentCamp,
                $data['hero_post_type'],
                $data['hero_race'],
                $data['hero_class'],
                $data['name']
            )
        ]);
    }

    protected function getRaiseMeasurableJobs()
    {
        $jobs = collect();
        $this->npc->heroes->each(function (Hero $hero) use (&$jobs) {
            $jobsForHero = $this->findMeasurablesToRaise->execute($hero)->map(function ($raiseArray) {
                return new RaiseMeasurableJob($raiseArray['measurable'], $raiseArray['amount']);
            })->values(); // "values()" remove keys (measurable-type names) so they don't override each for each hero
            $jobs = $jobs->merge($jobsForHero);
        });
        return $jobs;
    }

    protected function getEquipItemJobs()
    {
        $jobs = collect();
        $equipArrays = $this->findItemsToEquip->execute($this->npc);
        $equipArrays->each(function ($equipArray) use (&$jobs) {
            /** @var Hero $hero */
            $hero = $equipArray['hero'];
            /** @var Collection $items */
            $items = $equipArray['items'];
            $jobsForHero = $items->map(function (Item $item) use ($hero) {
                return new EquipItemForHeroJob($item, $hero);
            });
            $jobs = $jobs->merge($jobsForHero);
        });
        return $jobs;
    }
}
