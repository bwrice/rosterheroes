<template>
    <MapCard :view-box="viewBox">
        <ProvinceVector
            v-for="(province, uuid) in provincesForContinent"
            :key="uuid"
            :province="province"
            :route-link="true"
        ></ProvinceVector>
    </MapCard>
</template>

<script>

    import {mapGetters} from 'vuex';
    import { continentMixin } from '../../../../../mixins/continentMixin';
    import MapCard from "../../../map/MapCard";
    import ProvinceVector from "../../../map/ProvinceVector";

    export default {
        name: "ContinentView",
        components: {ProvinceVector, MapCard},
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
