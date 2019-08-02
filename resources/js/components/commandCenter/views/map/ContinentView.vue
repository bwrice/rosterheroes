<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <v-card>
            <Realm>
                <ProvinceVector v-for="(province, uuid) in provincesForContinent" :key="uuid" :province="province"></ProvinceVector>
            </Realm>
        </v-card>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import { continentMixin } from '../../../../mixins/continentMixin';
    import Realm from "./Realm";
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "ContinentView",
        components: {ProvinceVector, Realm},
        mixins: [
            continentMixin
        ],

        computed: {
            ...mapGetters([
                '_provinces',
                '_continent',
            ]),
            // needed for continent mixin
            continent() {
                return this._continent;
            }
        }
    }
</script>

<style scoped>

</style>