<?php

namespace App\Domain\Models;

use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailSubscription
 * @package App
 *
 * @property int $id
 * @property string $name
 *
 * @property Collection $users
 */
class EmailSubscription extends Model
{

    public const NEWSLETTER = 'newsletter';
    public const SQUAD_NOTIFICATIONS = 'squad notifications';

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * @return static
     */
    public static function squadNotifications()
    {
        /** @var static $sub */
        $sub = static::query()->where('name', '=', self::SQUAD_NOTIFICATIONS)->firstOrFail();
        return $sub;
    }

    /**
     * @return static
     */
    public static function newsletter()
    {
        /** @var static $sub */
        $sub = static::query()->where('name', '=', self::NEWSLETTER)->firstOrFail();
        return $sub;
    }
}
