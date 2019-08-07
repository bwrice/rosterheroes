<template>
    <v-flex class="xs12 lg8 offset-lg2">
        <MapCard :view-box="viewBox">
            <ProvinceVector
                v-for="(province, uuid) in provincesForTerritory"
                :key="uuid"
                :province="province"
                :route-link="true"
            ></ProvinceVector>
        </MapCard>
    </v-flex>
</template>

<script>

    import {mapGetters} from 'vuex';

    import MapCard from "../../../map/MapCard";
    import { territoryMixin } from '../../../../../mixins/territoryMixin';
    import ProvinceVector from "../../../map/ProvinceVector";

    export default {
        name: "TerritoryView",

        components: {
            MapCard,
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
