<?php


namespace App\Factories\Models;


use App\Chest;
use Illuminate\Support\Str;

class ChestFactory
{
    /** @var SquadFactory|null */
    protected $squadFactory;

    protected $squadID;

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
            'gold' => rand(100, 999)
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
}
