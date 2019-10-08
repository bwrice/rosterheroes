<template>
    <SvgIconSheet
        :color="'#C8DDE0'"
        :svg="heroRace.svg"
        :classes-object="{'pa-1': true}"
    >
    </SvgIconSheet>
</template>

<script>
    import SvgIconSheet from "./SvgIconSheet";
    import {mapGetters} from 'vuex';

    export default {
        name: "HeroRaceIcon",
        components: {SvgIconSheet},
        props: {
            heroRaceId: {
                type: Number,
                required: true
            }
        },
        computed: {
            ...mapGetters([
                '_heroRaceByID'
            ]),
            heroRace() {
                return this._heroRaceByID(this.heroRaceId);
            }
        }
    }
</script>

<style scoped>

</style>
