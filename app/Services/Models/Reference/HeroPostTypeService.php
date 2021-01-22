<?php


namespace App\Services\Models\Reference;


use App\Domain\Behaviors\HeroPostTypes\DwarfPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\ElfPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\HumanPostTypeBehavior;
use App\Domain\Behaviors\HeroPostTypes\OrcPostTypeBehavior;
use App\Domain\Models\HeroPostType;
use Illuminate\Database\Eloquent\Collection;

class HeroPostTypeService extends ReferenceService
{
    public function __construct()
    {
        $this->behaviors[HeroPostType::ORC] = app(OrcPostTypeBehavior::class);
        $this->behaviors[HeroPostType::DWARF] = app(DwarfPostTypeBehavior::class);
        $this->behaviors[HeroPostType::ELF] = app(ElfPostTypeBehavior::class);
        $this->behaviors[HeroPostType::HUMAN] = app(HumanPostTypeBehavior::class);

    }

    protected function all(): Collection
    {
        return HeroPostType::all();
    }
}
