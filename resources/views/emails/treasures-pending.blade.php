<?php
/**
 * @var \App\Domain\Models\Squad $squad
 * @var \App\Domain\Models\EmailSubscription $emailSub
 * @var string $title
 * @var string $message
 */
?>

@component('mail::message')

# {{$title}}

![Treasure Chest]({{url('/images/treasures.png')}})

{{$message}}

@component('mail::button', ['url' => url('/command-center/' . $squad->slug)])
Visit Command Center
@endcomponent
<a href="{{Illuminate\Support\Facades\URL::signedRoute('emails.unsubscribe', ['user' => $squad->user->uuid, 'emailSubscription' => $emailSub->id])}}">Unsubscribe to {{$emailSub->name}} emails</a>
<br>
<a href="{{Illuminate\Support\Facades\URL::signedRoute('emails.unsubscribe', ['user' => $squad->user->uuid, 'emailSubscription' => 'all'])}}">Unsubscribe to all emails from Roster Heroes</a>
@endcomponent
