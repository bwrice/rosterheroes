<template>
    <v-card>
        <v-layout>
            <v-flex class="xs12">
                <MapViewPort :view-box="currentViewBox">
                    <slot>
                        <!-- Default Slot: ProvinceVector components slotted here -->
                    </slot>
                </MapViewPort>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex class="xs7 md9">
                <slot name="footer-content">

                </slot>
            </v-flex>
            <v-flex class="xs5 md3 pa-1">
                <v-layout align-space-between wrap>
                    <v-flex class="xs6">
                        <v-layout justify-center>
                            <v-btn fab small @click="panUp">
                                <v-icon dark>arrow_drop_up</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-space-around>
                            <v-btn fab small @click="panLeft">
                                <v-icon dark>arrow_left</v-icon>
                            </v-btn>
                            <v-btn fab small @click="panRight">
                                <v-icon dark>arrow_right</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-center>
                            <v-btn fab small @click="panDown">
                                <v-icon dark>arrow_drop_down</v-icon>
                            </v-btn>
                        </v-layout>
                    </v-flex>
                    <v-flex class="xs6">
                        <v-layout justify-center class="pa-1">
                            <v-btn fab small @click="zoomIn">
                                <v-icon dark>add</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-center class="pa-1">
                            <v-btn fab small @click="zoomOut">
                                <v-icon dark>remove</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-center>
                            <v-btn small @click="restViewBox">
                                Reset
                            </v-btn>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </v-card>
</template>

<script>
    import MapViewPort from "./MapViewPort";
    export default {
        name: "MapCard",
        components: {MapViewPort},
        props: {
            viewBox: {
                type: Object,
                default: function() {
                    return {
                        pan_x: 0,
                        pan_y: 0,
                        zoom_x: 315,
                        zoom_y: 240,
                    };
                }
            }
        },
        created() {
            this.originalViewBox = _.cloneDeep(this.viewBox);
            this.currentViewBox = _.cloneDeep(this.viewBox);
        },

        data() {
            return {
                originalViewBox: {},
                currentViewBox: {}
            }
        },

        watch: {
            viewBox: function(newValue) {
                this.originalViewBox = _.cloneDeep(newValue);
                this.currentViewBox = _.cloneDeep(newValue);
            }
        },

        methods: {
            panUp() {
                this.currentViewBox.pan_y -= (.1 * this.currentViewBox.zoom_y);
            },
            panDown() {
                this.currentViewBox.pan_y += (.1 * this.currentViewBox.zoom_y);
            },
            panLeft() {
                this.currentViewBox.pan_x -= (.1 * this.currentViewBox.zoom_x);
            },
            panRight() {
                this.currentViewBox.pan_x += (.1 * this.currentViewBox.zoom_x);
            },
            restViewBox() {
                this.currentViewBox = _.cloneDeep(this.originalViewBox);
            },
            zoomIn() {
                /*
                By panning 10% but zooming 80% (instead of 90), we zoom the center of the SVG
                 */
                this.panRight();
                this.panDown();
                this.currentViewBox.zoom_x *= .8;
                this.currentViewBox.zoom_y *= .8;
            },
            zoomOut() {
                /*
                We have to pan after we adjust the zoom to reverse zoomIn() effect
                 */
                this.currentViewBox.zoom_x /= .8;
                this.currentViewBox.zoom_y /= .8;
                this.panLeft();
                this.panUp();
            }
        }
    }
</script>

<style scoped>

</style>
