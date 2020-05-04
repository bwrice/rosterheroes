<template>
    <MapLocationPanel
        :view-box="territory.viewBox"
        :location-name="territory.name"
        :handle-explore="exploreTerritory"
    >
        <TerritoryVector
            :territory="territory"
            :interactive="false"
        ></TerritoryVector>
    </MapLocationPanel>
</template>

<script>
    import Territory from "../../../models/Territory";
    import TerritoryVector from "./TerritoryVector";
    import MapLocationPanel from "./MapLocationPanel";

    export default {
        name: "TerritoryPanel",
        components: {MapLocationPanel, TerritoryVector},
        props: {
            territory: {
                type: Territory,
                required: true
            }
        },
        methods: {
            exploreTerritory() {
                this.territory.goToRoute(this.$router, this.$route);
            }
        }
    }
</script>

<style scoped>

</style>
