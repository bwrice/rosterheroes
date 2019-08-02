<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-card>
            <Realm v-if="inContinentMode">
                <ContinentVector v-for="(continent, id) in this._continents" :key="id" :continent="continent"></ContinentVector>
            </Realm>
            <Realm v-else>
                <ProvinceVector v-for="(province, uuid) in this._provinces" :key="uuid" :province="province"></ProvinceVector>
            </Realm>
        </v-card>
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
        computed: {
            ...mapGetters([
                '_provinces',
                '_territories',
                '_continents'
            ]),
            inContinentMode: function() {
                return this.mode === 'continent';
            }
        }
    }
</script>

<style scoped>

</style>