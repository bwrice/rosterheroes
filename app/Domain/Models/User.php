<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasUuid;
use App\EmailSubscription;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property string $email
 * @property string $name
 *
 * @property EloquentCollection $squads
 * @property EloquentCollection $emailSubscriptions
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function squads()
    {
        return $this->hasMany(Squad::class);
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'uuid' => $this->uuid
        ];
    }

    public function maxSquadsReached()
    {
        return $this->squads()->count() >= 1;
    }

    public function emailSubscriptions()
    {
        return $this->belongsToMany(EmailSubscription::class)->withTimestamps();
    }
}
