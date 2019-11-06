<template>
    <ExploreMapCard :view-box="continent.realmViewBox">
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

    import ProvinceVector from "../../../map/ProvinceVector";
    import ExploreMapCard from "../../../map/ExploreMapCard";
    import Continent from "../../../../../models/Continent";

    export default {
        name: "ContinentView",
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
                '_continentBySlug',
                '_provincesByContinentID'
            ]),
            continent() {
                let slug = this.$route.params.continentSlug;
                let continent = this._continentBySlug(slug);
                return continent ? continent : new Continent({});
            },
            provinces() {
                return this._provincesByContinentID(this.continent.id);
            }
        }
    }
</script>

<style scoped>

</style>
