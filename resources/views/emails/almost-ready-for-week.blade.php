<?php
/**
* @var \App\Domain\Models\Squad $squad
* @var \App\Domain\Models\Week $week
* @var \App\Domain\Collections\HeroCollection $heroesWithoutSpirits
* @var \Illuminate\Database\Eloquent\Collection $campaignStops
* @var \App\Domain\Models\EmailSubscription $emailSub
* @var string $title
* @var string $message
*/
?>

@component('mail::message')

# Your squad, {{$squad->name}}, is almost ready for the week of {{$week->adventuring_locks_at->timezone('America/New_York')->toDayDateTimeString()}}

@if($heroesWithoutSpirits->isNotEmpty())
## Heroes need attention
@if($heroesWithoutSpirits->count() > 1)
{{$squad->name}} has {{$heroesWithoutSpirits->count()}} heroes who need players to embody them. Heroes without player spirits cannot fight
in your campaign for the week.
@else
{{$squad->name}}'s hero, {{$heroesWithoutSpirits->first()->name}}, needs a player spirit. Heroes without player spirits cannot fight
in your campaign for the week.
@endif
@component('mail::table')
| Hero          | Player Spirit |
|:-------------:|:-------------:|
@foreach($squad->heroes as $hero)
| {{$hero->name}} | {{$hero->playerSpirit ? $hero->playerSpirit->playerFullName() : '(none)'}} |
@endforeach
@endcomponent
@endif

@if($questsAvailable || $sideQuestsAvailable)
## Current week's campaign needs attention
@if($campaignStops->isEmpty())
Your squad, {{$squad->name}}, has not joined any quests or side-quests for the current week. Without joining any quests, you cannot
gain experience or earn any treasures. Head to the command center and build your campaign to join quests and side-quests.
@else
{{$squad->name}}'s campaign has available quests and side-quest to join. More quests means
more experience and more treasures! Head over to the command center and finish building out your campaign.
@endif
@component('mail::table')
| Quest | Side Quests Available |
|:-----:|:---------------------:|
@foreach($campaignStops as $campaignStop)
| {{$campaignStop->quest->name}} | {{$squad->getSideQuestsPerQuest() - $campaignStop->sideQuestResults->count()}} |
@endforeach
@if($questsAvailable)
@foreach(range(1, $questsAvailable) as $count)
| (No Quest) | {{$squad->getSideQuestsPerQuest()}} |
@endforeach
@endif
@endcomponent
@endif

@component('mail::button', ['url' => url('/command-center/' . $squad->slug)])
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
