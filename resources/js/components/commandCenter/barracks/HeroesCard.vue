<template>
    <v-row no-gutters>
        <v-col cols="12">
            <span class="title font-weight-thin">HEROES</span>
        </v-col>
        <v-col
            cols="12"
            v-for="(hero, uuid) in heroes"
            :key="uuid"
        >
            <HeroSummaryPanel
                :hero="hero"
            >
            </HeroSummaryPanel>
        </v-col>
    </v-row>
</template>

<script>
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
