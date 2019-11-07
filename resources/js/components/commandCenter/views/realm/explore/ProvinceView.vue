<template>
    <ExploreMapCard :view-box="province.viewBox">
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

    import ProvinceVector from "../../../realm/ProvinceVector";
    import ExploreMapCard from "../../../realm/ExploreMapCard";

    import {mapGetters} from 'vuex';

    export default {
        name: "ProvinceView",
        components: {
            ExploreMapCard,
            ProvinceVector
        },

        methods: {
            navigateToProvince(province) {
                province.goToRoute(this.$router, this.$route);
            },
        },

        computed: {
            ...mapGetters([
                '_provinceBySlug',
                '_provincesByUuids'
            ]),
            province() {
                return this._provinceBySlug(this.$route.params.provinceSlug);
            },
            borders() {
                return this._provincesByUuids(this.province.borderUuids);
            }
        }
    }
</script>

<style scoped>

</style>
