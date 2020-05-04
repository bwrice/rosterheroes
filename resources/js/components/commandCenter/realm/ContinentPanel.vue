<template>
    <MapLocationPanel
        :view-box="continent.viewBox"
        :location-name="continent.name"
        :handle-explore="exploreContinent"
    >
        <ContinentVector
            :continent="continent"
            :interactive="false"
        ></ContinentVector>
    </MapLocationPanel>
</template>

<script>
    import Continent from "../../../models/Continent";
    import MapLocationPanel from "./MapLocationPanel";
    import ContinentVector from "./ContinentVector";

    export default {
        name: "ContinentPanel",
        components: {ContinentVector, MapLocationPanel},
        props: {
            continent: {
                type: Continent,
                required: true
            }
        },
        methods: {
            exploreContinent() {
                this.continent.goToRoute(this.$router, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
