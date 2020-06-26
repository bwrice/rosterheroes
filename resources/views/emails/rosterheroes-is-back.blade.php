@component('mail::message')
# Roster Heroes is Back!

![Roster Heroes]({{ config('app.url') . '/images/rh_mobile_screens.png' }})

After a 3 year hiatus, the fantasy sports MMORPG is back and better than ever. Completely rebuilt from the ground up,
there's now a massive realm to explore and you can now use players from the NFL, NBA, MLB & NHL and play all year long!
<br>
### Still free to play!

@component('mail::button', ['url' => config('app.url')])
Come Join the Action
@endcomponent

@endcomponent
