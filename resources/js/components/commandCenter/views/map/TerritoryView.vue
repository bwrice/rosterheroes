<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <Realm :view-box="viewBox">
            <ProvinceVector v-for="(province, uuid) in provincesForTerritory" :key="uuid" :province="province"></ProvinceVector>
        </Realm>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';
    import Realm from "./Realm";
    import { territoryMixin } from '../../../../mixins/territoryMixin';
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "TerritoryView",

        components: {
            Realm,
            ProvinceVector
        },

        mixins: [
            territoryMixin
        ],

        computed: {
            ...mapGetters([
                '_provinces',
                '_territory',
            ]),
            // needed for territory mixin
            territory() {
                return this._territory;
            },
            viewBox() {
                return this._territory.realm_view_box;
            }
        }
    }
</script>

<style scoped>

</style>