<?php


namespace App\Services;


use App\Notifications\AdminNotifiable;
use Illuminate\Notifications\Notification;

class Admin
{
    public function notify(Notification $notification)
    {
        /** @var AdminNotifiable $notifiable */
        $notifiable = app(AdminNotifiable::class);
        $notifiable->notify($notification);
    }
}
