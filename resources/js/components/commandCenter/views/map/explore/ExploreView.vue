<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-layout>
            <v-flex class="xs12">
                <router-view></router-view>
            </v-flex>
        </v-layout>
    </v-flex>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    export default {
        name: "ExploreView",

        watch:{
            $route (to) {
                if (to.params.continentSlug
                    && to.params.continentSlug !== this._continent.slug) {
                    this.setContinentBySlug(to.params.continentSlug);

                } else if (to.params.territorySlug
                    && to.params.territorySlug !== this._territory.slug) {
                    this.setTerritoryBySlug(to.params.territorySlug);

                } else if (to.params.provinceSlug
                    && to.params.provinceSlug !== this._province.slug) {
                    this.setProvinceBySlug(to.params.provinceSlug);
                }
            }
        },

        methods: {
            ...mapActions([
                'setContinentBySlug',
                'setTerritoryBySlug',
                'setProvinceBySlug',
            ])
        },
        computed: {
            ...mapGetters([
                '_continent',
                '_territory',
                '_province',
            ]),
        }
    }
</script>

<style scoped>

</style>
