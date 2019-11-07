<template>
    <ExploreMapCard :view-box="territory.realmViewBox">
        <ProvinceVector
            v-for="(province, uuid) in provinces"
            :key="uuid"
            :province="province"
            @provinceClicked="navigateToProvince"
        ></ProvinceVector>
    </ExploreMapCard>
</template>

<script>

    import {mapGetters} from 'vuex';

    import ProvinceVector from "../../../realm/ProvinceVector";
    import ExploreMapCard from "../../../realm/ExploreMapCard";

    export default {
        name: "TerritoryView",

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
                '_territoryBySlug',
                '_provincesByTerritoryID'
            ]),
            territory() {
                return this._territoryBySlug(this.$route.params.territorySlug);
            },
            provinces() {
                return this._provincesByTerritoryID(this.territory.id);
            }
        }
    }
</script>

<style scoped>

</style>
