<template>
    <MapCard :view-box="viewBox">
        <ProvinceVector
            v-for="(province, uuid) in provincesForContinent"
            :key="uuid"
            :province="province"
            @provinceClicked="navigateToProvince"
        ></ProvinceVector>
    </MapCard>
</template>

<script>

    import {mapGetters} from 'vuex';

    import { continentMixin } from '../../../../../mixins/continentMixin';
    import MapCard from "../../../map/MapCard";
    import ProvinceVector from "../../../map/ProvinceVector";

    export default {
        name: "ContinentView",
        components: {
            ProvinceVector,
            MapCard
        },
        mixins: [
            continentMixin
        ],

        methods: {
            navigateToProvince(province) {
                console.log("Navigate to province");
                let provinceRoute = this.provinceRoute(this._squad, province);
                this.$router.push(provinceRoute);
            },
            provinceRoute(squad, province) {
                return {
                    name: 'explore-province',
                    params: {
                        squadSlug: squad.slug,
                        provinceSlug: province.slug
                    }
                }
            }
        },

        computed: {
            ...mapGetters([
                '_provinces',
                '_continent',
                '_squad'
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
