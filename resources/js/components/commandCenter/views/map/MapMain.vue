<template>
    <v-flex class="xs12">
        <v-card class="pa-1">
            <v-layout>
                <v-flex class="xs5">
                    <MapViewPort :tile="false"></MapViewPort>
                </v-flex>
            </v-layout>
        </v-card>
    </v-flex>
</template>

<script>
    import MapViewPort from "../../map/MapViewPort";
    import Squad from "../../../../models/Squad";
    import Province from "../../../../models/Province";
    export default {
        name: "MapMain",
        components: {
            MapViewPort
        },
        async mounted() {
            let squadSlug = this.$route.params.squadSlug;
            let squad = new Squad({slug: squadSlug});
            let currentLocation = await Province.custom(squad, 'current-location').$get();
            console.log("Current Location");
            console.log(currentLocation);
        }
    }
</script>

<style scoped>

</style>
