<template>
    <v-card>
        <v-card-title>
            <h3>{{hero.name}}</h3>
            <PositionChip v-for="position in heroPositions" v-bind:key="position.id" :position="position"></PositionChip>
        </v-card-title>
        <slot name="body">
            <!-- Body Content-->
        </slot>
    </v-card>
</template>

<script>
    import PositionChip from "./PositionChip";

    export default {
        name: "HeroRosterCard",
        components: {PositionChip},
        props: ['hero'],

        computed: {
            heroPositions: function() {
                return this.hero.heroRace.positions;
            }
        }
    }
</script>

<style scoped>

</style>