<template>
    <MapViewPort :ocean-color="oceanColor">
        <ProvinceVector
            v-for="(province, uuid) in this._provinces"
            :key="uuid"
            :province="province"
            :fill-color="minimMapProvinceColor(province)"
        >
        </ProvinceVector>
    </MapViewPort>
</template>

<script>
    import MapViewPort from "./MapViewPort";
    import ProvinceVector from "./ProvinceVector";
    import {travelMixin} from "../../../mixins/travelMixin";

    export default {
        name: "TravelRouteMap",
        props: {
            oceanColor: {
                type: String,
                default: '#000000'
            }
        },
        components: {
            ProvinceVector,
            MapViewPort,
        },
        mixins: [
            travelMixin
        ]
    }
</script>

<style scoped>

</style>
