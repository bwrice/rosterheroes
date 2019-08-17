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

    import {mapGetters} from 'vuex';

    import {provinceNavigationMixin} from "../../../../../mixins/provinceNavigationMixin";

    import ProvinceVector from "../../../map/ProvinceVector";
    import ExploreMapCard from "../../../map/ExploreMapCard";

    export default {
        name: "ProvinceView",
        components: {
            ExploreMapCard,
            ProvinceVector
        },

        mixins: [
            provinceNavigationMixin
        ],

        computed: {
            ...mapGetters([
                '_province',
                '_borders',
                '_provinces'
            ]),
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
            },
            borders() {
                let borderUuids = this.province.borders.map((border) => border.uuid);
                return this._provinces.filter(function(province) {
                    return borderUuids.includes(province.uuid);
                })
            }
        }
    }
</script>

<style scoped>

</style>
