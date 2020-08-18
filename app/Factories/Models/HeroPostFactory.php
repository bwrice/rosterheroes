<?php


namespace App\Factories\Models;


use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroPostType;

class HeroPostFactory
{
    protected $squadID;

    protected $heroPostTypeID;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var HeroPost $heroPost */
        $heroPost = HeroPost::query()->create(array_merge([
            'hero_post_type_id' => $this->getHeroPostTypeID(),
            'squad_id' => $this->getSquadID(),
        ], $extra));

        return $heroPost;
    }

    protected function getSquadID()
    {
        if ($this->squadID) {
            return $this->squadID;
        }
        return SquadFactory::new()->create()->id;
    }

    protected function getHeroPostTypeID()
    {
        if ($this->heroPostTypeID) {
            return $this->heroPostTypeID;
        }
        return HeroPostType::query()->inRandomOrder()->first()->id;
    }

    public function forSquad(int $squadID)
    {
        $clone = clone $this;
        $clone->squadID = $squadID;
        return $clone;
    }

    public function forHeroPostType(int $heroPostTypeID)
    {
        $clone = clone $this;
        $clone->heroPostTypeID = $heroPostTypeID;
        return $clone;
    }
}
