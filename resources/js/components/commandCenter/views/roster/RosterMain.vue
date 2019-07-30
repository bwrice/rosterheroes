<template>
    <v-container class="pa-2">
        <v-card>
            <span class="display-3 px-1">{{this._squad.availableSpiritEssence}}</span> Spirit Essence Available
            <div v-for="(hero, uuid) in this.heroes">
                <HeroRosterCard :hero="hero">
                    <template slot="body">
                        <div v-if="hero.playerSpirit">
                            <PlayerSpiritPanel :player-spirit="getPlayerSpirit(hero.playerSpirit)">
                                <template v-slot:spirit-actions>
                                    <EditSpiritButton :hero="hero"></EditSpiritButton>
                                    <RemoveSpiritButton :hero="hero" :player-spirit="getPlayerSpirit(hero.playerSpirit)"></RemoveSpiritButton>
                                </template>
                            </PlayerSpiritPanel>
                        </div>
                        <div v-else>
                            <EditSpiritButton :hero="hero"></EditSpiritButton>
                        </div>
                    </template>
                </HeroRosterCard>
            </div>
        </v-card>
    </v-container>
</template>

<script>


    import HeroRosterCard from '../../roster/HeroRosterCard';
    import HeroSpiritSelection from '../../roster/HeroSpiritSelection';
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import EditSpiritButton from "../../roster/EditSpiritButton";
    import PlayerSpiritPanel from "../../roster/PlayerSpiritPanel";

    import PlayerSpirit from "../../../../models/PlayerSpirit";

    import { mapGetters } from 'vuex'

    export default {
        name: "RosterMain",
        components: {
            HeroRosterCard,
            HeroSpiritSelection,
            EditSpiritButton,
            RemoveSpiritButton,
            PlayerSpiritPanel
        },
        computed: {
            ...mapGetters([
                '_squad',
                '_availableSpiritEssence'
            ]),
            heroes: function() {
                let _heroes = [];
                if (this._squad.heroPosts) {
                    this._squad.heroPosts.forEach(function (heroPost) {
                        if (heroPost.hero) {
                            _heroes.push(heroPost.hero);
                        }
                    });
                }
                return _heroes;
            }
        },
        methods: {
            getPlayerSpirit: function(playerSpirit) {
                return new PlayerSpirit(playerSpirit)
            }
        }
    }
</script>

<style scoped>

</style>