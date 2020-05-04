<template>
    <MapLocationPanel
        :view-box="province.viewBox"
        :location-name="province.name"
        :handle-explore="exploreProvince"
    >
        <ProvinceVector :province="province"></ProvinceVector>
    </MapLocationPanel>
</template>

<script>
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
        methods: {
            exploreProvince() {
                this.province.goToRoute(this.$router, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
