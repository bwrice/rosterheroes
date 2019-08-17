<template>
    <ExploreMapCard :view-box="territory.realm_view_box">
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
                '_territories'
            ]),
            territory() {
                let slug = this.$route.params.territorySlug;
                let territory = this._territories.find((territory) => territory.slug === slug);
                if (territory) {
                    return territory;
                }
                return {
                    'name': '',
                    'slug': null,
                    'realm_view_box': {
                        'pan_x': 0,
                        'pan_y': 0,
                        'zoom_x': 315,
                        'zoom_y': 240
                    }
                };
            }
        }
    }
</script>

<style scoped>

</style>
