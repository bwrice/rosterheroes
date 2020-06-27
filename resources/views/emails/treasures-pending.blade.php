<?php
/**
 * @var \App\Domain\Models\Squad $squad
 * @var int $unopenedChestsCount
 */
?>

@component('mail::message')

# {{$unopenedChestsCount}} Unopened Chests Awaiting!

![Treasure Chest]({{ config('app.url') . '/images/treasures.png' }})

Your squad, {{$squad->name}}, has {{$unopenedChestsCount}} treasure chests that
won from campaigns and still haven't been opened. There's loot to be had!
Visit {{$squad->name}}'s command center and open those chests.

@component('mail::button', ['url' => config('app.url') . '/command-center/' . $squad->slug])
Visit Command Center
@endcomponent

@endcomponent
