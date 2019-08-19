<template>
    <v-col cols="12">
        <BarracksHeroCard
            v-for="(hero, uuid) in _barracksHeroes"
            :key="uuid"
            :hero="hero"
        >
        </BarracksHeroCard>
    </v-col>
</template>

<script>

    import {mapGetters} from 'vuex';
    import BarracksHeroCard from "../../barracks/BarracksHeroCard";

    export default {
        name: "BarracksMain",
        components: {BarracksHeroCard},
        created() {
            console.log("Barracks Created!");
        },
        computed: {
            ...mapGetters([
                '_barracksHeroes'
            ])
        }
    }
</script>

<style scoped>

</style>
