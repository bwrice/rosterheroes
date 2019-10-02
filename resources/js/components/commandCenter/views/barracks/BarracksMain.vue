<template>
    <v-col cols="12">
        <HeroesCard :heroes="_barracksHeroes"></HeroesCard>
        <MobileStorageCard :mobile-storage="_mobileStorage"></MobileStorageCard>
    </v-col>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BarracksHeroCard from "../../barracks/HeroSummaryPanel";
    import MobileStorageCard from "../../barracks/MobileStorageCard";
    import HeroesCard from "../../barracks/HeroesCard";

    export default {
        name: "BarracksMain",
        components: {HeroesCard, MobileStorageCard, BarracksHeroCard},

        computed: {
            ...mapGetters([
                '_mobileStorage',
                '_barracksHeroes'
            ])
        }
    }
</script>

<style scoped>

</style>
