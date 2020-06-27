<?php
/**
 * @var \App\Domain\Models\Squad $squad
 * @var string $title
 * @var string $message
 */
?>

@component('mail::message')

# {{$title}}

![Treasure Chest]({{ config('app.url') . '/images/treasures.png' }})

{{$message}}

@component('mail::button', ['url' => config('app.url') . '/command-center/' . $squad->slug])
Visit Command Center
@endcomponent

@endcomponent
