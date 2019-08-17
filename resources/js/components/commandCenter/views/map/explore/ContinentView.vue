<template>
    <ExploreMapCard :view-box="continent.realm_view_box">
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

    import ProvinceVector from "../../../map/ProvinceVector";
    import ExploreMapCard from "../../../map/ExploreMapCard";

    export default {
        name: "ContinentView",
        components: {
            ExploreMapCard,
            ProvinceVector
        },
        mixins: [
            continentMixin,
            provinceNavigationMixin
        ],

        computed: {
            ...mapGetters([
                '_provinces',
                '_continents'
            ]),
            continent() {
                let slug = this.$route.params.continentSlug;
                let continent = this._continents.find((continent) => continent.slug === slug);
                if (continent) {
                    return continent;
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
