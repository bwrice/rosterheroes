<?php


namespace App\Facades;


use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\Admin
 *
 * @method static notify(Notification $notification)
 */
class Admin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'admin';
    }
}
