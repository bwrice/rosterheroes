<template>
    <ExploreMapCard :view-box="viewBox">
        <ProvinceVector
            v-for="(province, uuid) in provincesForTerritory"
            :key="uuid"
            :province="province"
            @provinceClicked="navigateToProvince"
        ></ProvinceVector>
    </ExploreMapCard>
</template>

<script>

    import {mapGetters} from 'vuex';

    import {territoryMixin} from '../../../../../mixins/territoryMixin';
    import {provinceNavigationMixin} from "../../../../../mixins/provinceNavigationMixin";

    import ProvinceVector from "../../../map/ProvinceVector";
    import ExploreMapCard from "../../../map/ExploreMapCard";

    export default {
        name: "TerritoryView",

        components: {
            ExploreMapCard,
            ProvinceVector
        },

        mixins: [
            territoryMixin,
            provinceNavigationMixin
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
