<template>
    <v-sheet tile :color="oceanColor">
        <svg xmlns="http://www.w3.org/2000/svg"
             version="1.1"
             :viewBox="viewBoxString">
            <slot>
                <!-- Default Slot: ProvinceVector components slotted here -->
            </slot>
        </svg>
    </v-sheet>
</template>

<script>
    export default {
        name: "MapViewPort",
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
            },
            oceanColor: {
                type: String,
                default: '#d5f5f5'
            }
        },
        computed: {
            viewBoxString() {
                return this.viewBox.pan_x + ' ' + this.viewBox.pan_y + ' ' + this.viewBox.zoom_x + ' ' + this.viewBox.zoom_y;
            }
        }
    }
</script>

<style scoped>

</style>
