<?php


namespace App\Notifications;


use Illuminate\Notifications\Notifiable;

class AdminNotifiable
{
    use Notifiable;

    public function routeNotificationForMail()
    {
        return config('admin.email');
    }

    public function routeNotificationForSlack()
    {
        return config('admin.slack.webhook_url');
    }

    public function getKey()
    {
        return 1;
    }
}
