<?php
/**
 * @var string $playerSpiritName
 * @var \App\Domain\Models\Hero $hero
 * @var \App\Domain\Models\EmailSubscription $emailSub
 * @var string $title
 * @var string $message
 */
?>

@component('mail::message')

# {{$playerSpiritName}} is no longer valid for this week's campaigns

Visit {{$hero->squad->name}}'s command center and embody {{$hero->name}} with a new player spirit!

@component('mail::button', ['url' => url('/command-center/' . $hero->squad->slug)])
Visit Command Center
@endcomponent
@slot('subcopy')
Want to stop receiving these types of emails?
<br>
<a href="{{Illuminate\Support\Facades\URL::signedRoute('emails.unsubscribe', ['user' => $squad->user->uuid, 'emailSubscription' => $emailSub->id])}}">Unsubscribe to {{$emailSub->name}} emails</a>
<br>
<a href="{{Illuminate\Support\Facades\URL::signedRoute('emails.unsubscribe', ['user' => $squad->user->uuid, 'emailSubscription' => 'all'])}}">Unsubscribe to all emails from Roster Heroes</a>
@endslot
@endcomponent
