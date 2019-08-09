<template>
    <ExploreMapCard :view-box="this._province.view_box">
        <!-- Borders -->
        <ProvinceVector
            v-for="(province, uuid) in this._borders"
            :key="uuid"
            :province="province"
            @provinceClicked="navigateToProvince"
        >
        </ProvinceVector>

        <!-- Province -->
        <ProvinceVector :province="this._province" :highlight="true"></ProvinceVector>
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
                '_borders'
            ])
        }
    }
</script>

<style scoped>

</style>
