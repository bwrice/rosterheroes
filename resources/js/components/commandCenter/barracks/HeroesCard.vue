<template>
    <v-card>
        <HeroSummaryPanel
            v-for="(hero, uuid) in heroes"
            :key="uuid"
            :hero="hero"
        >
        </HeroSummaryPanel>
    </v-card>
</template>

<script>
    import {mapGetters} from 'vuex';
    import HeroSummaryPanel from "./HeroSummaryPanel";

    export default {
        name: "HeroesCard",
        components: {HeroSummaryPanel},
        props: {
            heroes: {
                type: Array,
                required: true
            }
        },
        computed: {
            //
        }
    }
</script>

<style scoped>

</style>
