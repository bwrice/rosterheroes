<template>
    <ExploreMapCard :view-box="viewBox">
        <ProvinceVector
            v-for="(province, uuid) in provincesForContinent"
            :key="uuid"
            :province="province"
            @provinceClicked="navigateToProvince"
        ></ProvinceVector>
    </ExploreMapCard>
</template>

<script>

    import {mapGetters} from 'vuex';

    import {continentMixin} from '../../../../../mixins/continentMixin';
    import {provinceNavigationMixin} from "../../../../../mixins/provinceNavigationMixin";

    import MapCard from "../../../map/MapCard";
    import ProvinceVector from "../../../map/ProvinceVector";
    import ExploreMapCard from "../../../map/ExploreMapCard";

    export default {
        name: "ContinentView",
        components: {
            ExploreMapCard,
            ProvinceVector,
            MapCard
        },
        mixins: [
            continentMixin,
            provinceNavigationMixin
        ],

        computed: {
            ...mapGetters([
                '_provinces',
                '_continent'
            ]),
            // needed for continent mixin
            continent() {
                return this._continent;
            },
            viewBox() {
                return this._continent.realm_view_box;
            },
        }
    }
</script>

<style scoped>

</style>
