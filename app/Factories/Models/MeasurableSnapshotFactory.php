<?php


namespace App\Factories\Models;


use App\Domain\Models\MeasurableSnapshot;
use Illuminate\Support\Str;

class MeasurableSnapshotFactory
{
    protected ?int $heroSnapshotID = null;
    protected ?int $measurableID = null;
    protected ?MeasurableFactory $measurableFactory = null;
    protected ?HeroSnapshotFactory $heroSnapshotFactory = null;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var MeasurableSnapshot $measurableSnapshot */
        $measurableSnapshot = MeasurableSnapshot::query()->create([
            'uuid' => (string) Str::uuid(),
            'hero_snapshot_id' => $this->getHeroSnapshotID(),
            'measurable_id' => $this->getMeasurableID(),
            'pre_buffed_amount' => $preBuffedAmount = rand(25, 100),
            'buffed_amount' => $buffedAmount = $preBuffedAmount + rand(0, 40),
            'final_amount' => $buffedAmount - rand(0, 20)
        ]);

        return $measurableSnapshot;
    }

    protected function getHeroSnapshotID()
    {
        if ($this->heroSnapshotID) {
            return $this->heroSnapshotID;
        }

        $heroSnapshotFactory = $this->heroSnapshotFactory ?: HeroSnapshotFactory::new();

        return $heroSnapshotFactory->create()->id;
    }

    protected function getMeasurableID()
    {
        if ($this->measurableID) {
            return $this->measurableID;
        }

        $measurableFactory = $this->measurableFactory ?: MeasurableFactory::new();

        return $measurableFactory->create()->id;
    }

    public function withHeroSnapshotFactory(HeroSnapshotFactory $heroSnapshotFactory)
    {
        $clone = clone $this;
        $clone->heroSnapshotFactory = $heroSnapshotFactory;
        return $clone;
    }

    public function withMeasurableFactory(MeasurableFactory $measurableFactory)
    {
        $clone = clone $this;
        $clone->measurableFactory = $measurableFactory;
        return $clone;
    }

}
