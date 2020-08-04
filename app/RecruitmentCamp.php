<?php

namespace App;

use App\Domain\Interfaces\Merchant;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\Province;
use App\Domain\Models\Traits\HasUniqueNames;
use App\Domain\Traits\HasNameSlug;
use App\Domain\Traits\HasUuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RecruitmentCamp
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $province_id
 * @property string $name
 * @property string $slug
 *
 * @property Province $province
 *
 * @property Collection $heroPostTypes
 * @property Collection $heroClasses
 */
class RecruitmentCamp extends Model implements Merchant
{
    use HasNameSlug;
    use HasUuid;
    use HasUniqueNames;

    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getMerchantType(): string
    {
        return 'recruitment camp';
    }

    public function heroPostTypes()
    {
        return $this->belongsToMany(HeroPostType::class)->withTimestamps();
    }

    public function heroClasses()
    {
        return $this->belongsToMany(HeroClass::class)->withTimestamps();
    }
}
