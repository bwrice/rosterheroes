
@extends('layouts.tailwind-gradient')

@section('content')
    <div class="container max-w-5xl mx-auto flex-1 flex flex-col items-center justify-center px-2">
        <div class="px-6 py-8 my-12 rounded shadow-md text-black w-full" style="background-color: #d5e1e8">
            <h1 class="font-bold text-3xl my-2 text-center text-teal-900">Frequently Asked Questions</h1>
            <ul class="mx-8 list-disc text-xl underline font-bold text-teal-800">
                <li><a href="#hero-class">What are hero classes?</a></li>
                <li><a href="#hero-race">What are hero races?</a></li>
                <li><a href="#player-spirit">What is a player spirit?</a></li>
                <li><a href="#spirit-essence">What is spirit essence?</a></li>
                <li><a href="#spirit-energy">What does player spirit energy do?</a></li>
                <li><a href="#campaign">What is a campaign?</a></li>
                <li><a href="#quests">What are quests and side-quests?</a></li>
                <li><a href="#join-quest">I've joined a quest or side-quest, now what?</a></li>
                <li><a href="#find-quest">How can I find a quest?</a></li>
                <li><a href="#quest-travel">I joined a quest one week, and now it's no longer there?</a></li>
                <li><a href="#gold">What is gold used for?</a></li>
                <li><a href="#favor">What is favor used for?</a></li>
                <li><a href="#combat-positions">How do combat positions work?</a></li>
                <li><a href="#attributes">What are attributes for?</a></li>
                <li><a href="#resources">What are resources for?</a></li>
                <li><a href="#attacks">What are an item's attacks?</a></li>
                <li><a href="#attack-damage">How is attack damage calculated?</a></li>
                <li><a href="#fantasy-power">How is fantasy power calculated?</a></li>
                <li><a href="#attack-speed">What is attack speed?</a></li>
                <li><a href="#speed-calculation">How is attack speed calculated?</a></li>
                <li><a href="#wagon">What is a wagon?</a></li>
                <li><a href="#stash">What is a stash?</a></li>
            </ul>

            <h2 id="hero-class" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are hero classes?</h2>
            <p>
                Hero classes include
                @foreach($heroClasses as $heroClass)
                    <?php /** @var \App\Domain\Models\HeroClass $heroClass */ ?>
                    @if (! $loop->last)
                        <span class="font-semibold">{{ucfirst($heroClass->name)}}</span>,
                    @else
                        and <span class="font-semibold">{{ucfirst($heroClass->name)}}</span>
                    @endif
                @endforeach
                . They affect the base amounts and cost of raising for both attributes,
                @foreach($attributeTypes as $attributeType)
                    <?php /** @var \App\Domain\Models\MeasurableType $attributeType */ ?>
                    @if (! $loop->last)
                        <span class="font-semibold">{{ucfirst($attributeType->name)}}</span>,
                    @else
                        and <span class="font-semibold">{{ucfirst($attributeType->name)}}</span>
                    @endif
                @endforeach
                as well as resources,
                @foreach($resourceTypes as $resourceType)
                    <?php /** @var \App\Domain\Models\MeasurableType $resourceType */ ?>
                    @if (! $loop->last)
                        <span class="font-semibold">{{ucfirst($resourceType->name)}}</span>,
                    @else
                        and <span class="font-semibold">{{ucfirst($resourceType->name)}}.</span>
                    @endif
                @endforeach
            </p>

            <h2 id="hero-race" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are hero races?</h2>
            <p>
                Hero races include
                @foreach($heroRaces as $heroRace)
                    <?php /** @var \App\Domain\Models\HeroRace $heroRace */ ?>
                    @if (! $loop->last)
                        <span class="font-semibold">{{ucfirst($heroRace->name)}}</span>,
                    @else
                        and <span class="font-semibold">{{ucfirst($heroRace->name)}}</span>.
                    @endif
            @endforeach
                Hero races determine the positions of available player-spirits to embody your squad's heroes.
            </p>
            <h2 id="player-spirit" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is a player spirit?</h2>
            <p>
                A player spirit embodies a hero to increase that hero's stats. Their stats come from real players from the NFL, NBA, MLB,
                and NHL who have games that Sunday. Player spirits can only be
                added or removed from a hero if their game hasn't started yet that week.
            </p>
            <h2 id="spirit-essence" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is spirit essence?</h2>
            <p>
                Spirit essence is the cost to embody a hero with a player spirit. You'll have a limited amount for your squad to use on
                all of your heroes. If you've ever played salary cap league, think of spirit essence as your salary cap. Your squad's
                spirit essence will reset each week, so use as much as needed to build the perfect roster.
            </p>
            <h2 id="spirit-energy" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What does player spirit energy do?</h2>
            <p>
                Spirit energy rangers from 25 to 400. When a player's stats are converted into fantasy points, a higher spirit energy
                will result in more fantasy points. Spirit energy is calculated based on how popular a player spirit is. If a lot of
                heroes embody the same player spirit, it will have a lower spirit energy and if few or no heroes are embodied by a player spirit,
                it will have a higher energy.
            </p>
            <h2 id="campaign" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is a campaign?</h2>
            <p>
                A campaign is the combination of quests and side-quests joined by squad each week.
            </p>
            <h2 id="quests" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are quests and side-quests?</h2>
            <p>
                Quests and side-quests are you how your squad will make the majority of it's progress in Roster Heroes. You can find them
                at various provinces throughout the realm. Quests are fought cooperatively with all the other squads that have also joined
                that quest, while side-quests are fought individually by your squad.
            </p>
            <h2 id="join-quest" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">I've joined a quest or side-quest, now what?</h2>
            <p>
                Roster Heroes runs on weekly cycles. You can join a limited amount of quests and side-quests each week. At the end of week,
                Sunday night into Monday, all the quest and side-quests will process and you're squad will have any experience, favor and treasure
                chests earned from that week. You can also replay the results to see how the combat went.
            </p>
            <h2 id="find-quest" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">How can I find a quest?</h2>
            <p>
                Quests are located all throughout the realm. The quickest way to find them is using the map. When viewing a province
                from the map, you'll see limited information about the province including the amount of quests located there. You can
                mark that province for travel and then use the travel tool to build a route to your destination.
            </p>
            <h2 id="quest-travel" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">I joined a quest one week, and now it's no longer there?</h2>
            <p>
                Quests will not always be at the same province each week. Although some quests are stationary, most travel throughout the realm. How they
                travel is determined by their travel type. Some will move to any provinces in the same territory, continent, or any province border
                from it's current location, and some can even move anywhere in the realm.
            </p>
            <h2 id="gold" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is gold used for?</h2>
            <p>
                Gold is the primary currency of Roster Heroes. Use gold at various merchants to buy new items, learn new spells,
                enlist more heroes and test your squad in combat before the quests start for that week.
            </p>
            <h2 id="favor" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is favor used for?</h2>
            <p>
                Your squad and heroes can earn various medals and badges when fighting in quests and side-quests. Favor is used
                to unlock those medals and badges to show off the strength of your squad.
            </p>
            <h2 id="combat-positions" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">How do combat positions work?</h2>
            <p>
                Combatants (heroes, minions and titans) can fight at three combat positions, front-line, back-line, and high-ground.
                Combat positions both determine which attacks are available for the combatant and which attacks will target the combatant.
                Front-line combatants will have all attacks from any combat position at their disposal, however, they will be targeted
                by the majority of the opposing combatants' attacks. On the other hand, high-ground combatants will have only high-ground attacks
                available to them but will be targeted by the least (if any) attacks from the opposing combatants.
            </p><br>
            <p>
                Combat positions can change during combat. Any combat position missing combatants, or all of their combatants have fallen in battle,
                will be inherited by the closest combat position with healthy combatants. If your squad's front-line heroes all fall in combat, the back-line will
                inherit the front-line combat position. If your back-line heroes fall as well, then your high-ground will inherit both the
                front-line and back-line combat positions. This means your high-ground heroes can now use their front-line or back-line attacks
                they weren't able to at the start of combat, but, more importantly, they will be the target of every opposing combatant's attack.
            </p><br>
            <p>
                The inheritance of combat positions guarantees attacks will find a target. So if your squad joins a side-quest against a pack
                of werewolves, where all the werewolves fight from the front-line, it's perfectly fine to have heroes on the high-ground using bows,
                which have attacks that target the enemy's back-line, because even though there are no werewolves fighting from the back-line,
                the front-line werewolves inherited both the back-line and high-ground combat positions.
            </p>
            <h2 id="attributes" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are attributes for?</h2>
            <p>
                Attributes include Strength, Valor, Agility, Focus and Intelligence. All six attributes are used in calculating the damage modifier
                for an item's attacks. For example, axes damage modifier is improved by raising strength and valor, daggers by raising agility and focus,
                and wands by raising aptitude and intelligence.
            </p><br>
            <p>
                Each attribute also has a special characteristic. Strength increase the capacity for a hero to carry more and heavier items. Valor will
                increase a hero's Health. Agility increase the combat speed of an item's attacks as well as increasing Stamina. Focus, and Aptitude will
                increase a hero's spell power. Finally, Intelligence will increase a hero's Mana.
            </p>
            <h2 id="resources" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are resources for?</h2>
            <p>
                Resources include Health, Stamina and Mana. Health is the most important resource. If a hero loses all of its health during combat,
                it will have fallen in battle. Stamina and Mana are used by attacks during combat. Running out of stamina or mana will limit a hero's
                attacks. Mana is also used to cast spells. The more powerful the spell, the more mana it will cost.
            </p>
            <h2 id="qualities" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are qualities for?</h2>
            <p>
                Qualities include Passion, Balance, Honor, Prestige and Wrath. Qualities will increase the amount of fantasy points given for a particular
                stat, such as rushing touchdown, or home-run. You can see how many fantasy points a given stat type will give a hero, and the
                bonus % from the hero's qualities on the stat bonuses card of a hero.
            </p>
            <h2 id="attacks" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What are an item's attacks?</h2>
            <p>
                Weapon type items will have various attacks. When a weapon is equipped, if the hero has the resources to use the attack, meets the attack's
                requirements and fights from, or inherited, a combat position within the attacker combat-position, it can use the attack.
            </p>
            <h2 id="attack-damage" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">How is attack damage calculated?</h2>
            <p>
                Damage is calculated from a base-damage amount plus a damage modifier, which is affected by a hero's attributes, and fantasy power.
            </p>
            <h2 id="fantasy-power" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">How is fantasy power calculated?</h2>
            <p>
                Fantasy power is calculated from the sum of each player spirit's stat amount by the points-per-amount as well as multiplied by
                the hero's quality bonus percent which coincides with that stat-type. That sum is then increased or decreased based on the player spirit's energy.
                Fantasy power can never be less than zero.
            </p>
            <h2 id="attack-speed" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is attack speed?</h2>
            <p>
                Combat is fought in moments. For each moment a combatant's attacks have a chance of triggering. An attack's speed
                is the chance it has of triggering in a given moment. An attack speed of 5 will have a 5% chance of triggering at any
                given moment, or an expectation of triggering about once per 20 moments.
            </p>
            <h2 id="speed-calculation" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">How is attack speed calculated?</h2>
            <p>
                Attack speed is dependent on the attack, the item base of the item with the attack, and can be increased by a hero's Agility.
            </p>
            <h2 id="wagon" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is a wagon?</h2>
            <p>
                A wagon is your squad's mobile storage. It will travel with your squad and you can store a limited amount of items
                in it. Items unequipped from hero's on received from opening treasure chests will prioritize going into a squad's wagon,
                but if there is no room it will end up in a stash.
            </p>
            <h2 id="stash" class="font-bold text-1xl mt-3 mb-1 text-teal-900 underline">What is a stash?</h2>
            <p>
                A stash is a place where squads can hide items in a province. A squad can only have one stash per province, but it has no
                maximum capacity. At the end of the week, scavengers might come across and steal items from your stash. It's best
                to move items from your squad's stashes before the week ends.
            </p>
        </div>
    </div>
@endsection
