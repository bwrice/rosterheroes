<?php


namespace App\Factories\Models;


use App\Domain\Behaviors\ProvinceEvents\SquadEntersProvinceBehavior;
use App\Domain\Models\Province;
use App\Domain\Models\ProvinceEvent;
use App\Domain\Models\Squad;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;

class ProvinceEventFactory
{
    protected ?Province $province = null;
    protected ?Squad $squad = null;
    protected ?string $eventType = null;
    protected ?CarbonInterface $happenedAt = null;
    protected array $extra = [];

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return ProvinceEvent
     * @throws \Exception
     */
    public function create(array $extra = []): ProvinceEvent
    {
        if (! $this->eventType) {
            throw new \Exception("Event type not set. Use one of the setter methods before calling create");
        }

        /** @var ProvinceEvent $provinceEvent */
        $provinceEvent = ProvinceEvent::query()->create(array_merge(
            [
                'uuid' => (string) Str::uuid(),
                'province_id' => $this->getProvince()->id,
                'squad_id' => $this->getSquad() ? $this->getSquad()->id : null,
                'event_type' => $this->eventType,
                'happened_at' => $this->getHappenedAt(),
                'extra' => $this->extra,
            ],
            $extra
        ));
        return $provinceEvent;
    }

    protected function getProvince()
    {
        if ($this->province) {
            return $this->province;
        }

        $this->province = Province::query()->inRandomOrder()->first();
        return $this->province;
    }

    protected function getSquad($createIfNull = false)
    {
        if ($this->squad) {
            return $this->squad;
        }
        if ($createIfNull) {
            return $this->createSquad();
        }
        return null;
    }

    protected function createSquad()
    {
        if (! $this->squad) {
            $this->squad = SquadFactory::new()->create();
        }
        return $this->squad;
    }

    public function at(CarbonInterface $happenedAt)
    {
        $clone = clone $this;
        $clone->happenedAt = $happenedAt;
        return $clone;
    }

    protected function getHappenedAt()
    {
        if ($this->happenedAt) {
            return $this->happenedAt;
        }
        $this->happenedAt = now();
        return $this->happenedAt;
    }

    public function forSquad(Squad $squad)
    {
        $clone = clone $this;
        $clone->squad = $squad;
        return $clone;
    }

    public function forProvince(Province $province)
    {
        $clone = clone $this;
        $clone->province = $province;
        return $clone;
    }

    public function squadEntersProvince(Province $provinceLeft = null, int $goldCost = null)
    {
        $clone = clone $this;
        $clone->createSquad();
        $provinceLeft = $provinceLeft ?: $clone->getProvince()->borders()->inRandomOrder()->first();
        $goldCost = $goldCost ?: rand(10, 999);

        $clone->extra = SquadEntersProvinceBehavior::buildExtraArray($provinceLeft, $goldCost);
        $clone->eventType = ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE;

        return $clone;
    }
}
