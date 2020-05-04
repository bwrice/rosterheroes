<template>
    <MapLocationPanel
        :view-box="province.viewBox"
        :location-name="province.name"
        :handle-explore="exploreProvince"
    >
        <!-- Borders -->
        <ProvinceVector
            v-for="(province, uuid) in borders"
            :key="uuid"
            :province="province"
            :fill-color="'#808080'"
        >
        </ProvinceVector>

        <!-- Province -->
        <ProvinceVector :province="province" :highlight="true"></ProvinceVector>
    </MapLocationPanel>
</template>

<script>
    import {mapGetters} from 'vuex';
    import Province from "../../../models/Province";
    import MapLocationPanel from "./MapLocationPanel";
    import ProvinceVector from "./ProvinceVector";

    export default {
        name: "ProvincePanel",
        components: {ProvinceVector, MapLocationPanel},
        props: {
            province: {
                type: Province,
                required: true
            }
        },

        computed: {
            ...mapGetters([
                '_provincesByUuids'
            ]),
            borders() {
                return this._provincesByUuids(this.province.borderUuids);
            }
        },
        methods: {
            exploreProvince() {
                this.province.goToRoute(this.$router, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
