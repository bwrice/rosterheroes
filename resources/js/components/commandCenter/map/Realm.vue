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
                            <v-btn fab small>
                                <v-icon dark>arrow_drop_up</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-space-around>
                            <v-btn fab small>
                                <v-icon dark>arrow_left</v-icon>
                            </v-btn>
                            <v-btn fab small>
                                <v-icon dark>arrow_right</v-icon>
                            </v-btn>
                        </v-layout>
                        <v-layout justify-center>
                            <v-btn fab small>
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
                            <v-btn small>
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
        computed: {
            viewBoxString() {
                return this.viewBox.pan_x + ' ' + this.viewBox.pan_y + ' ' + this.viewBox.zoom_x + ' ' + this.viewBox.zoom_y;
            },
            oceanColor() {
                return '#d5f5f5'
            }
        }
    }
</script>

<style scoped>

</style>
