<template>
    <v-col cols="12">
        <v-row no-gutters>
            <v-col cols="12">
                <v-card class="my-2">
                    <v-card-title>{{ _barracksFocusedHero.name }}</v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="12">
            </v-col>
        </v-row>
    </v-col>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';
    import MeasurablePanel from "../../barracks/MeasurablePanel";

    export default {
        name: "BarracksHeroView",
        components: {MeasurablePanel},

        mounted() {
            this.setBarracksFocusedHeroBySlug(this.$route.params.heroSlug);
        },

        watch: {
            $route (to) {
                if (to.params.heroSlug && to.params.heroSlug !== this._barracksFocusedHero.slug) {
                    this.setBarracksFocusedHeroBySlug(to.params.heroSlug);
                }
            }
        },

        methods: {
            ...mapActions([
                'setBarracksFocusedHeroBySlug'
            ]),
        },

        computed: {
            ...mapGetters([
                '_barracksHeroes',
                '_barracksFocusedHero'
            ]),
        }
    }
</script>

<style scoped>

</style>
