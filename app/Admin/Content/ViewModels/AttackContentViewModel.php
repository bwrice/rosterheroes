<?php


namespace App\Admin\Content\ViewModels;


use App\Admin\Content\Sources\AttackSource;
use App\Domain\Models\Attack;
use App\Facades\Content;
use App\Services\ContentService;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class AttackContentViewModel implements ContentViewModel
{
    /**
     * @var CarbonInterface
     */
    protected $lastUpdated;
    /**
     * @var Collection
     */
    protected $attackSources;

    public function __construct(CarbonInterface $lastUpdated, Collection $attackSources)
    {
        $this->lastUpdated = $lastUpdated;
        $this->attackSources = $attackSources;
    }

    public static function build()
    {
        $dataArray = json_decode(file_get_contents(resource_path('json/content/attacks.json')), true);
        $lastUpdated = Date::createFromTimestamp($dataArray['last_updated']);

        return new static($lastUpdated, Content::attacks());
    }

    public function getTitle(): string
    {
        return 'Attacks';
    }

    public function totalCount(): int
    {
        return $this->attackSources->count();
    }

    protected function getOutOfSyncAttacks()
    {
        $attacks = Attack::query()->get();
        return $this->attackSources->filter(function (AttackSource $attackSource) use ($attacks) {
            /** @var Attack $match */
            $match = $attacks->first(function (Attack $attack) use ($attackSource) {
                return $attackSource->getUuid() === (string) $attack->uuid;
            });
            if (! $match) {
                return true;
            }
            return ! $attackSource->isSynced($match);
        });
    }

    public function outOfSynCount(): int
    {
        return $this->getOutOfSyncAttacks()->count();
    }

    public function lastUpdated(): CarbonInterface
    {
        return $this->lastUpdated;
    }

    public function createURL(): string
    {
        return '/admin/content/attacks/create';
    }
}
