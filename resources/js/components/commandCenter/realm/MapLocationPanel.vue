<template>
    <v-sheet color="#466661" class="my-1 rounded-sm">
        <v-row no-gutters>
            <v-col cols="12">
                <v-row no-gutters align="center">
                    <v-col cols="3" class="pa-1">
                        <MapViewPort :view-box="viewBox" :rounded-size="'small'">
                            <slot>
                                <!-- Default Slot -->
                            </slot>
                        </MapViewPort>
                    </v-col>
                    <v-col cols="7">
                        <v-row no-gutters justify="center">
                            <span class="subtitle-1 font-weight-light">{{locationName}}</span>
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
                                @click="handleExplore"
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
    import MapViewPort from "./MapViewPort";
    import ViewBox from "../../../models/ViewBox";
    export default {
        name: "MapLocationPanel",
        components: {MapViewPort},
        props: {
            viewBox: {
                type: ViewBox,
                required: true
            },
            locationName: {
                type: String,
                required: true
            },
            handleExplore: {
                type: Function,
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
