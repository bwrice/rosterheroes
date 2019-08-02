<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-layout>
            <v-flex class="xs12">
                <Realm v-if="inContinentMode">
                    <ContinentVector v-for="(continent, id) in this._continents" :key="id" :continent="continent"></ContinentVector>
                </Realm>
                <Realm v-else>
                    <ProvinceVector v-for="(province, uuid) in this._provinces" :key="uuid" :province="province"></ProvinceVector>
                </Realm>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex class="xs12 pa-2">
                <v-chip :input-value="inContinentMode" @click="setMode('continent')" label>Continents</v-chip>
                <v-chip :input-value="inTerritoryMode" @click="setMode('territory')"  label>Territories</v-chip>
            </v-flex>
        </v-layout>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import ProvinceVector from "./ProvinceVector";
    import Realm from "./Realm";
    import ContinentVector from "./ContinentVector";

    export default {
        name: "MapMain",
        components: {
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
            setMode: function(mode) {
                this.mode = mode;
            }
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents'
            ]),
            inContinentMode: function() {
                return this.mode === 'continent';
            },
            inTerritoryMode: function() {
                return this.mode === 'territory';
            }
        }
    }
</script>

<style scoped>

</style>