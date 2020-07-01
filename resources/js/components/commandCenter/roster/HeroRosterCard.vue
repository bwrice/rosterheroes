<template>
    <v-sheet color="#576269" class="mb-2 pt-1 rounded">
        <slot name="body">
            <!-- Body Content-->
        </slot>
        <v-row no-gutters justify="space-between" align="center">
            <span class="subtitle-2 mx-2">{{hero.name}}</span>
            <PositionChipList :positions="positions"></PositionChipList>
        </v-row>
    </v-sheet>
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
