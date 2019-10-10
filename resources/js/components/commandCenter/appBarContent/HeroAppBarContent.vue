<template>
    <div style="display: flex; flex-direction: row">
        <div style="width: 36px">
            <HeroRaceIcon :hero-race-id="hero.heroRaceID"></HeroRaceIcon>
        </div>
        <div style="width: 36px; margin-right: 10px">
            <HeroClassIcon :hero-class-id="hero.heroClassID"></HeroClassIcon>
        </div>
        <div style="display: flex; align-items: center">
            <v-toolbar-title>
                {{ hero.name }}
            </v-toolbar-title>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import HeroRaceIcon from "../global/HeroRaceIcon";
    import HeroClassIcon from "../global/HeroClassIcon";

    export default {
        name: "HeroAppBar",
        components: {HeroClassIcon, HeroRaceIcon},
        computed: {
            ...mapGetters([
                '_focusedHero'
            ]),
            hero() {
                return this._focusedHero(this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
