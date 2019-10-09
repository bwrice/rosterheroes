<template>
    <BaseAppBar :app-bar-title="hero.name">
        <template v-slot:before-title>
            <div style="width: 36px">
                <HeroRaceIcon :hero-race-id="hero.heroRaceID"></HeroRaceIcon>
            </div>
            <div style="width: 36px; margin-right: 10px">
                <HeroClassIcon :hero-class-id="hero.heroClassID"></HeroClassIcon>
            </div>
        </template>
    </BaseAppBar>
</template>

<script>
    import BaseAppBar from "./BaseAppBar";
    import {mapGetters} from 'vuex';
    import HeroRaceIcon from "../global/HeroRaceIcon";
    import HeroClassIcon from "../global/HeroClassIcon";

    export default {
        name: "HeroAppBar",
        components: {HeroClassIcon, HeroRaceIcon, BaseAppBar},
        computed: {
            ...mapGetters([
                '_focusedBarracksHero'
            ]),
            hero() {
                return this._focusedBarracksHero(this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
