<?php


namespace App\Factories\Models;


use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use Illuminate\Support\Str;

class MeasurableFactory
{
    /** @var int */
    protected $measurableTypeID;

    /** @var int */
    protected $heroID;

    /** @var HeroFactory */
    protected $heroFactory;

    /** @var int */
    protected $amountRaised = 0;

    protected function __construct()
    {
        $this->heroFactory = HeroFactory::new();
    }

    public static function new()
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Measurable
     * @throws \Exception
     */
    public function create(array $extra = []): Measurable
    {
        /** @var Measurable $measurable */
        $measurable = Measurable::query()->create([
            'uuid' => (string) Str::uuid(),
            'amount_raised' => $this->amountRaised,
            'measurable_type_id' => $this->measurableTypeID ?: MeasurableType::query()->inRandomOrder()->first()->id,
            'hero_id' => $this->heroID ?: $this->heroFactory->create()->id
        ]);
        return $measurable;
    }

    protected function forHero(Hero $hero)
    {
        $clone = clone $this;
        $clone->heroID = $hero->id;
        return $clone;
    }

    public function amountRaised(int $amountRaised)
    {
        $clone = clone $this;
        $clone->amountRaised = $amountRaised;
        return $clone;
    }
}
