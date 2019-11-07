<template>
    <v-card>
        <v-layout>
            <v-flex class="xs12">
                <MapViewPort :view-box="currentViewBox">
                    <slot>
                        <!-- Default slot for province vectors -->
                    </slot>
                </MapViewPort>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex class="xs7 md9">
                <v-layout>
                    <v-flex class="xs12">
                        <slot name="footer-content">
                            <!-- left footer content slot -->
                        </slot>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex class="xs5 md3 pa-1">
                <MapControls
                    @panUp="panUp"
                    @panDown="panDown"
                    @panLeft="panLeft"
                    @panRight="panRight"
                    @zoomIn="zoomIn"
                    @zoomOut="zoomOut"
                    @reset="restViewBox"
                ></MapControls>
            </v-flex>
        </v-layout>
    </v-card>
</template>

<script>
    import {viewBoxControlsMixin} from "../../../mixins/viewBoxControlsMixin";
    import MapControls from "./MapControls";
    import MapViewPort from "./MapViewPort";

    export default {
        name: "ExploreMapCard",
        props: {
            viewBox: {
                type: Object,
                default: function() {
                    return {
                        pan_x: 0,
                        pan_y: 0,
                        zoom_x: 315,
                        zoom_y: 240,
                    }
                }
            }
        },
        mounted() {
          this.setViewBox(this.viewBox);
        },
        watch: {
            viewBox: function(newValue) {
                this.setViewBox(newValue)
            }
        },
        components: {
            MapViewPort,
            MapControls
        },
        mixins: [
            viewBoxControlsMixin
        ]
    }
</script>

<style scoped>

</style>
