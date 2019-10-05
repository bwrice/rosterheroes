<template>
    <v-card>
        <v-card-title>Heroes</v-card-title>
        <v-card-text class="px-2 pb-2">
            <HeroSummaryPanel
                v-for="(hero, uuid) in heroes"
                :key="uuid"
                :hero="hero"
            >
            </HeroSummaryPanel>
        </v-card-text>
    </v-card>
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
