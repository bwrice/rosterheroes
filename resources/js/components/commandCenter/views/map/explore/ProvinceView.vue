<template>
    <ExploreMapCard :view-box="province.view_box">
        <!-- Borders -->
        <ProvinceVector
            v-for="(province, uuid) in borders"
            :key="uuid"
            :province="province"
            @provinceClicked="navigateToProvince"
        >
        </ProvinceVector>

        <!-- Province -->
        <ProvinceVector :province="province" :highlight="true"></ProvinceVector>
    </ExploreMapCard>
</template>

<script>

    import {provinceNavigationMixin} from "../../../../../mixins/provinceNavigationMixin";
    import {bordersMixin} from "../../../../../mixins/bordersMixin";

    import ProvinceVector from "../../../map/ProvinceVector";
    import ExploreMapCard from "../../../map/ExploreMapCard";

    export default {
        name: "ProvinceView",
        components: {
            ExploreMapCard,
            ProvinceVector
        },

        mixins: [
            provinceNavigationMixin,
            bordersMixin
        ],

        computed: {
            province() {
                let slug = this.$route.params.provinceSlug;
                let province = this._provinces.find((province) => province.slug === slug);
                if (province) {
                    return province;
                }
                return {
                    'slug': null,
                    view_box: {
                        'pan_x': 0,
                        'pan_y': 0,
                        'zoom_x': 315,
                        'zoom_y': 240,
                    },
                    borders: []
                };
            }
        }
    }
</script>

<style scoped>

</style>
