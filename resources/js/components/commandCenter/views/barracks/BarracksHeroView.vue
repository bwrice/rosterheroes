<template>
    <v-flex class="xs12">
        <v-layout>
            <v-flex class="xs12">
                <v-card>
                    <v-card-title>{{ _barracksFocusedHero.name }}</v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex class="xs12">
                <v-card>
                    <v-card-title>Attributes</v-card-title>
                    <MeasurablePanel
                        v-for="(attribute, uuid) in attributes"
                        :key="uuid"
                        :measurable="attribute"
                    ></MeasurablePanel>
                </v-card>
                <v-card>
                    <v-card-title>Resources</v-card-title>
                </v-card>
                <v-card>
                    <v-card-title>Qualities</v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
    </v-flex>
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

        watch:{
            $route (to) {
                if (to.params.heroSlug && to.params.heroSlug !== this._barracksFocusedHero.slug) {
                    this.setBarracksFocusedHeroBySlug(to.params.heroSlug);
                }
            }
        },

        methods: {
            ...mapActions([
                'setBarracksFocusedHeroBySlug'
            ])
        },

        computed: {
            ...mapGetters([
                '_barracksHeroes',
                '_barracksFocusedHero'
            ]),
            attributes() {
                return this._barracksFocusedHero.measurables.filter(function (measurable) {
                    return measurable.measurable_type.group === 'attribute';
                })
            }
        }
    }
</script>

<style scoped>

</style>
