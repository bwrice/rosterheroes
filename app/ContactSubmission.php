<?php

namespace App;

use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactSubmission
 * @package App
 *
 * @property string $name
 * @property string $email
 * @property string $reason
 * @property string $message
 * @property string $type
 *
 * @property User|null $user
 *
 */
class ContactSubmission extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
