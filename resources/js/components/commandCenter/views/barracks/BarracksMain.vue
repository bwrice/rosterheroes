<template>
    <v-col cols="12">
        <BarracksHeroCard
            v-for="(hero, uuid) in _barracksHeroes"
            :key="uuid"
            :hero="hero"
        >
        </BarracksHeroCard>
        <MobileStorageCard :mobile-storage="_mobileStorage"></MobileStorageCard>
    </v-col>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BarracksHeroCard from "../../barracks/BarracksHeroCard";
    import MobileStorageCard from "../../barracks/MobileStorageCard";

    export default {
        name: "BarracksMain",
        components: {MobileStorageCard, BarracksHeroCard},
        created() {
            console.log("Barracks Created!");
        },
        computed: {
            ...mapGetters([
                '_barracksHeroes',
                '_mobileStorage'
            ])
        }
    }
</script>

<style scoped>

</style>
