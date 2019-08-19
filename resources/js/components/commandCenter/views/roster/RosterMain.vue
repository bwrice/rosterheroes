<template>
    <v-col>
        <v-card>
            <span class="display-3 px-1">{{availableSpiritEssence}}</span> Spirit Essence Available
            <div v-for="(hero, uuid) in _rosterHeroes">
                <HeroRosterCard :hero="hero">
                    <template slot="body">
                        <div v-if="hero.playerSpirit">
                            <PlayerSpiritPanel :player-spirit="hero.playerSpirit">
                                <template v-slot:spirit-actions>
                                    <EditSpiritButton :hero="hero"></EditSpiritButton>
                                    <RemoveSpiritButton :hero="hero" :player-spirit="hero.playerSpirit"></RemoveSpiritButton>
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
    </v-col>
</template>

<script>


    import HeroRosterCard from '../../roster/HeroRosterCard';
    import HeroSpiritSelection from '../../roster/HeroSpiritSelection';
    import RemoveSpiritButton from "../../roster/RemoveSpiritButton";
    import EditSpiritButton from "../../roster/EditSpiritButton";
    import PlayerSpiritPanel from "../../roster/PlayerSpiritPanel";

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
                '_rosterHeroes',
                '_squad'
            ]),
            availableSpiritEssence() {

                let startingSpiritEssence = this._squad.spirit_essence;
                let usedEssence =  this._rosterHeroes.reduce(function (essence, hero) {
                    if (hero.playerSpirit) {
                        return essence + hero.playerSpirit.essence_cost;
                    }
                    return essence;
                }, 0);
                return startingSpiritEssence - usedEssence;
            }
        },
    }
</script>

<style scoped>

</style>
