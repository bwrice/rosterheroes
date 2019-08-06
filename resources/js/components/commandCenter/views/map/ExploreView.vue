<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-layout>
            <v-flex class="xs12">
                <Realm>
                    <template v-if="inContinentMode">
                        <ContinentVector v-for="(continent, id) in this._continents" :key="id" :continent="continent"></ContinentVector>
                    </template>
                    <template v-else>
                        <TerritoryVector v-for="(territory, id) in this._territories" :key="id" :territory="territory"></TerritoryVector>
                    </template>
                    <template slot="footer-content">
                        <v-layout>
                            <v-flex class="xs12">
                                <v-chip class="ma-1"
                                        :input-value="inContinentMode"
                                        @click="setRealmMapMode('continent')"
                                        filter
                                        filter-icon="mdi-eye"
                                        label
                                >
                                    Continents
                                </v-chip>
                                <v-chip class="ma-1"
                                        :input-value="inTerritoryMode"
                                        @click="setRealmMapMode('territory')"
                                        filter
                                        filter-icon="mdi-eye"
                                        label
                                >
                                    Territories
                                </v-chip>
                            </v-flex>
                        </v-layout>
                    </template>
                </Realm>
            </v-flex>
        </v-layout>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import ProvinceVector from "../../map/ProvinceVector";
    import Realm from "../../map/Realm";
    import ContinentVector from "../../map/ContinentVector";
    import TerritoryVector from "../../map/TerritoryVector";

    export default {
        name: "ExploreView",
        components: {
            TerritoryVector,
            ContinentVector,
            Realm,
            ProvinceVector
        },

        data: function() {
            return {
                mode: 'continent'
            }
        },

        methods: {
            ...mapActions([
                'setRealmMapMode',
            ]),
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents',
                '_realmMapMode'
            ]),
            inContinentMode: function() {
                return this._realmMapMode === 'continent';
            },
            inTerritoryMode: function() {
                return this._realmMapMode === 'territory';
            }
        }
    }
</script>

<style scoped>

</style>
