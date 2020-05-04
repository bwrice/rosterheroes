<template>
    <v-sheet color="#466661" class="my-1">
        <v-row no-gutters>
            <v-col cols="12">
                <v-row no-gutters align="center">
                    <v-col cols="3" class="pa-1">
                        <MapViewPort :view-box="territory.viewBox">
                            <TerritoryVector
                                :territory="territory"
                                :interactive="false"
                            ></TerritoryVector>
                        </MapViewPort>
                    </v-col>
                    <v-col cols="7">
                        <v-row no-gutters justify="center">
                            <span class="subtitle-1 font-weight-light">{{territory.name}}</span>
                        </v-row>
                    </v-col>
                    <v-col cols="2">
                        <v-row no-gutters justify="center">
                            <v-btn
                                fab
                                dark
                                small
                                color="#219cc2"
                                elevation="2"
                                @click="exploreTerritory"
                            >
                                <v-icon>explore</v-icon>
                            </v-btn>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
    import Territory from "../../../models/Territory";
    import MapViewPort from "./MapViewPort";
    import TerritoryVector from "./TerritoryVector";

    export default {
        name: "TerritoryPanel",
        components: {TerritoryVector, MapViewPort},
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
