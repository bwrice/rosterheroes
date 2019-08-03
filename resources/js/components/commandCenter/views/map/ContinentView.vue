<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <Realm :view-box="viewBox">
            <ProvinceVector v-for="(province, uuid) in provincesForContinent" :key="uuid" :province="province"></ProvinceVector>
        </Realm>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import { continentMixin } from '../../../../mixins/continentMixin';
    import Realm from "../../map/Realm";
    import ProvinceVector from "../../map/ProvinceVector";

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
            },
            viewBox() {
                return this._continent.realm_view_box;
            }
        }
    }
</script>

<style scoped>

</style>