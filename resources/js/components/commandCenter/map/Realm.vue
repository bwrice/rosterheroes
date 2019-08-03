<template>
    <v-card>
        <v-layout>
            <v-flex class="xs12">
                <v-sheet tile :color="oceanColor">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         version="1.1"
                         :viewBox="viewBoxString">
                        <slot>
                            <!-- ProvinceVector components slotted here -->
                        </slot>
                    </svg>
                </v-sheet>
            </v-flex>
        </v-layout>
        <v-layout>
            <v-flex class="xs8">

            </v-flex>
            <v-flex class="xs4">
                <v-btn></v-btn>
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