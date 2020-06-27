<?php


namespace App\Factories\Models;


use App\Domain\Models\Chest;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class ChestFactory
{
    /** @var SquadFactory|null */
    protected $squadFactory;

    protected $squadID;

    protected $openedAt;

    protected $chestBlueprintID = null;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var Chest $chest */
        $chest = Chest::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'squad_id' => $this->getSquadID(),
            'quality' => rand(1, 6),
            'size' => rand(1, 6),
            'gold' => rand(100, 999),
            'opened_at' => $this->openedAt,
            'chest_blueprint_id' => $this->chestBlueprintID
        ], $extra));
        return $chest;
    }

    protected function getSquadID()
    {
        if ($this->squadID) {
            return $this->squadID;
        }

        $squadFactory = $this->squadFactory ?: SquadFactory::new();
        return $squadFactory->create()->id;
    }

    public function forSquad(SquadFactory $squadFactory)
    {
        $clone = clone $this;
        $clone->squadFactory = $squadFactory;
        return $clone;
    }

    public function withSquadID(int $squadID)
    {
        $clone = clone $this;
        $clone->squadID = $squadID;
        return $clone;
    }

    public function opened(CarbonInterface $openedAt = null)
    {
        $clone = clone $this;
        $clone->openedAt = $openedAt ?: now();
        return $clone;
    }

    public function withChestBlueprintID(int $chestBlueprintID)
    {
        $clone = clone $this;
        $clone->chestBlueprintID = $chestBlueprintID;
        return $clone;
    }
}
