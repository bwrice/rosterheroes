<?php

namespace App\Domain\Models;

use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

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

    /**
     *
     * A helper method to quickly retrieve by uuid.
     *
     * @param string $uuid
     * @return static|null
     */
    public static function uuid(string $uuid)
    {
        /** @var static $model */
        $model = static::query()->where('uuid', $uuid)->first();
        return $model;
    }

}
