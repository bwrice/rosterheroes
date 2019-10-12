<template>
    <v-chip x-small label :color="color" class="px-1" text-color="#FFFFFF">{{position.abbreviation}}</v-chip>
</template>

<script>
    import Position from "../../../models/Position";
    import {mapGetters} from 'vuex';

    export default {
        name: "PositionChip",
        props: {
            position: {
                type: Position,
                required: true
            },
        },
        computed: {
            ...mapGetters([
                '_sportByID'
            ]),
            color() {
                let sport = this._sportByID(this.position.sportID);
                return sport.color;
            }
        }
    }
</script>

<style>

</style>
