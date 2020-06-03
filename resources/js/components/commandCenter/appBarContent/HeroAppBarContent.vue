<template>
    <div style="display: flex; flex-direction: row">
        <div style="width: 36px">
            <HeroRaceIcon :hero-race="heroRace"></HeroRaceIcon>
        </div>
        <div style="width: 36px; margin-right: 10px">
            <HeroClassIcon :hero-class="heroClass"></HeroClassIcon>
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
    import HeroRaceIcon from "../../icons/heroRaces/HeroRaceIcon";
    import HeroClassIcon from "../../icons/heroClasses/HeroClassIcon";

    export default {
        name: "HeroAppBar",
        components: {HeroClassIcon, HeroRaceIcon},
        computed: {
            ...mapGetters([
                '_focusedHero',
                '_heroRaceByID',
                '_heroClassByID'
            ]),
            hero() {
                return this._focusedHero(this.$route);
            },
            heroRace() {
                return this._heroRaceByID(this.hero.heroRaceID);
            },
            heroClass() {
                return this._heroClassByID(this.hero.heroClassID);
            }
        }
    }
</script>

<style scoped>

</style>
