<template>
    <v-card>
        <v-layout>
            <v-flex class="xs12">
                <MapViewPort :view-box="currentViewBox">
                    <template v-if="inContinentMode">
                        <ContinentVector v-for="(continent, id) in this._continents" :key="id" :continent="continent"></ContinentVector>
                    </template>
                    <template v-else>
                        <TerritoryVector v-for="(territory, id) in this._territories" :key="id" :territory="territory"></TerritoryVector>
                    </template>
                </MapViewPort>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex class="xs7 md9">
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
            </v-flex>
            <v-flex class="xs5 md3 pa-1">
                <MapControls
                    @panUp="panUp"
                    @panDown="panDown"
                    @panLeft="panLeft"
                    @panRight="panRight"
                    @zoomIn="zoomIn"
                    @zoomOut="zoomOut"
                    @reset="restViewBox"
                ></MapControls>
            </v-flex>
        </v-layout>
    </v-card>
</template>

<script>

    import {mapGetters} from 'vuex';
    import {mapActions} from 'vuex';

    import {viewBoxControlsMixin} from "../../../../../mixins/viewBoxControlsMixin";

    import MapCard from "../../../map/MapCard";
    import ContinentVector from "../../../map/ContinentVector";
    import TerritoryVector from "../../../map/TerritoryVector";
    import MapViewPort from "../../../map/MapViewPort";
    import MapControls from "../../../map/MapControls";

    export default {
        name: "RealmView",
        components: {
            MapControls,
            MapViewPort,
            TerritoryVector,
            ContinentVector,
            MapCard
        },

        mixins: [
            viewBoxControlsMixin
        ],

        data: function() {
            return {
                mode: 'continent'
            }
        },

        methods: {
            ...mapActions([
                'setRealmMapMode',
            ])
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
