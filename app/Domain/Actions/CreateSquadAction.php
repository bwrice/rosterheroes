<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/15/19
 * Time: 9:13 AM
 */

namespace App\Domain\Actions;


use App\Aggregates\SquadAggregate;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\Province;
use App\Domain\Models\Spell;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use Illuminate\Support\Str;

class CreateSquadAction
{
    /**
     * @var AddSpellToLibraryAction
     */
    protected $addSpellToLibraryAction;
    /**
     * @var RewardChestToSquad
     */
    protected $rewardChestToSquad;

    public function __construct(
        AddSpellToLibraryAction $addSpellToLibraryAction,
        RewardChestToSquad $rewardChestToSquad)
    {
        $this->addSpellToLibraryAction = $addSpellToLibraryAction;
        $this->rewardChestToSquad = $rewardChestToSquad;
    }

    /**
     * @param int $userID
     * @param string $name
     * @return Squad
     */
    public function execute(int $userID, string $name): Squad
    {
        /** @var Squad $squad */
        $squad = Squad::query()->create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $userID,
            'name' => $name,
            'squad_rank_id' => SquadRank::getStarting()->id,
            'mobile_storage_rank_id' => MobileStorageRank::getStarting()->id,
            'province_id' => Province::getStarting()->id,
            'gold' => Squad::STARTING_GOLD,
            'spirit_essence' => Squad::STARTING_ESSENCE,
            'favor' => Squad::STARTING_FAVOR,
            'experience' => Squad::STARTING_EXPERIENCE
        ]);

        $startingHeroPostTypes = HeroPostType::squadStarting();

        $heroPostTypes = HeroPostType::all();
        $startingHeroPostTypes->each(function (array $startingHeroPostType) use ($squad, $heroPostTypes) {

            for ($i = 1; $i <= $startingHeroPostType['count']; $i++) {
                /** @var HeroPostType $heroPostType */
                $heroPostType = $heroPostTypes->where('name', '=', $startingHeroPostType['name'])->first();
                $squad->heroPosts()->create([
                    'hero_post_type_id' => $heroPostType->id
                ]);
            }
        });

        $startingSpells = Spell::query()->whereIn('name', Squad::STARTING_SPELLS)->get();
        $startingSpells->each(function (Spell $spell) use ($squad) {
            $this->addSpellToLibraryAction->execute($squad, $spell);
        });

        /** @var ChestBlueprint $chestBlueprint */
        $chestBlueprint = ChestBlueprint::query()->where('description', '=', 'Newcomer Chest')->first();
        $this->rewardChestToSquad->execute($chestBlueprint, $squad, null);

        return $squad->fresh();
    }
}
