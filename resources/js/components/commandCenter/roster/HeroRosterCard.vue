<template>
    <v-card class="mx-1 my-1">
        <v-card-title>
            <h3>{{hero.name}}</h3>
            <PositionChipList :positions="positions"></PositionChipList>
        </v-card-title>
        <slot name="body">
            <!-- Body Content-->
        </slot>
    </v-card>
</template>

<script>
    import PositionChip from "./PositionChip";
    import PositionChipList from "./PositionChipList";

    import {mapGetters} from 'vuex';
    import Hero from "../../../models/Hero";

    export default {
        name: "HeroRosterCard",
        components: {PositionChipList, PositionChip},
        props: {
            hero: {
                type: Hero,
                required: true
            }
        },

        computed: {
            ...mapGetters([
                '_heroRaceByID',
                '_positionsFilteredByIDs'
            ]),
            positions() {
                let heroRace = this._heroRaceByID(this.hero.heroRaceID);
                return this._positionsFilteredByIDs(heroRace.positionIDs);
            }
        }
    }
</script>

<style scoped>

</style>
