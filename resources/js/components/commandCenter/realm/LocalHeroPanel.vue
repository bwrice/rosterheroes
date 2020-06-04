<template>
    <v-sheet color="rgba(0,0,0, .25)" class="mx-2 mb-1">
        <v-row no-gutters justify="center" align="center">
            <div style="flex: 0 0 10%" class="my-1 ml-1">
                <HeroRaceIcon :hero-race="heroRace"></HeroRaceIcon>
            </div>
            <div style="flex: 0 0 10%" class="my-1">
                <HeroClassIcon :hero-class="heroClass"></HeroClassIcon>
            </div>
            <div class="flex-grow-1"></div>
            <span class="title font-weight-light">{{localHero.name}}</span>
            <div class="flex-grow-1"></div>
        </v-row>
    </v-sheet>
</template>

<script>
    import {mapGetters} from 'vuex';
    import HeroClassIcon from "../../icons/heroClasses/HeroClassIcon";
    import HeroRaceIcon from "../../icons/heroRaces/HeroRaceIcon";
    import LocalHero from "../../../models/LocalHero";
    export default {
        name: "LocalHeroPanel",
        components: {HeroRaceIcon, HeroClassIcon},
        props: {
            localHero: {
                type: LocalHero,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_heroRaceByID',
                '_heroClassByID'
            ]),
            heroRace() {
                return this._heroRaceByID(this.localHero.heroRaceID);
            },
            heroClass() {
                return this._heroClassByID(this.localHero.heroClassID);
            }
        }
    }
</script>

<style scoped>

</style>
