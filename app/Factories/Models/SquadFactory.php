<?php


namespace App\Factories\Models;


use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\User;
use Illuminate\Support\Str;

class SquadFactory
{
    protected $userID;

    protected $provinceID;

    protected $squadRankID;

    protected $mobileStorageRankID;

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Squad
     * @throws \Exception
     */
    public function create(array $extra = []): Squad
    {
        /** @var Squad $squad */
        $squad = Squad::query()->create(array_merge(
            [
                'user_id' => $this->userID ?: factory(User::class)->create()->id,
                'uuid' => Str::uuid(),
                'name' => 'TestSquad_' . random_int(1,999999999),
                'province_id' => $this->provinceID ?: Province::getStarting()->id,
                'squad_rank_id' => $this->squadRankID ?: SquadRank::getStarting()->id,
                'mobile_storage_rank_id' => $this->mobileStorageRankID ?: MobileStorageRank::getStarting()->id,
                'spirit_essence' => Squad::STARTING_ESSENCE,
                'gold' => Squad::STARTING_GOLD,
                'experience' => Squad::STARTING_EXPERIENCE,
                'favor' => Squad::STARTING_FAVOR,
            ],
            $extra
        ));
        return $squad;
    }
}
