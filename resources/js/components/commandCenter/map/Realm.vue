<template>
    <v-card>
        <v-layout>
            <v-flex class="xs12">
                <v-sheet tile :color="oceanColor">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         version="1.1"
                         :viewBox="viewBoxString">
                        <slot>
                            <!-- Default Slot: ProvinceVector components slotted here -->
                        </slot>
                    </svg>
                </v-sheet>
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
                            <v-btn fab small>
                                <v-icon dark>add</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-center class="pa-1">
                            <v-btn fab small>
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
    export default {
        name: "Realm",
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
            }
        },

        computed: {
            viewBoxString() {
                return this.currentViewBox.pan_x + ' ' + this.currentViewBox.pan_y + ' ' + this.currentViewBox.zoom_x + ' ' + this.currentViewBox.zoom_y;
            },
            oceanColor() {
                return '#d5f5f5'
            }
        }
    }
</script>

<style scoped>

</style>
