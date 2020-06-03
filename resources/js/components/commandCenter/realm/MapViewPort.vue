<template>
    <v-row no-gutters class="py-2">
        <v-col cols="12">
            <v-sheet
                id="map-sheet"
                :tile="tile"
                :color="oceanColor"
                @click="click"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     version="1.1"
                     display="block"
                     :viewBox="viewBoxString"
                >
                    <slot>
                        <!-- Default Slot: ProvinceVector components slotted here -->
                    </slot>
                </svg>
            </v-sheet>
        </v-col>
    </v-row>
</template>

<script>
    import ViewBox from "../../../models/ViewBox";

    export default {
        name: "MapViewPort",
        props: {
            viewBox: {
                type: ViewBox,
                default: function() {
                    return new ViewBox({
                        panX: 0,
                        panY: 0,
                        zoomX: 315,
                        zoomY: 240,
                    });
                }
            },
            oceanColor: {
                type: String,
                default: '#d5f5f5'
            },
            tile: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            viewBoxString() {
                return this.viewBox.panX + ' ' + this.viewBox.panY + ' ' + this.viewBox.zoomX + ' ' + this.viewBox.zoomY;
            }
        },
        methods: {
            click(e) {
                this.$emit('click', e);
            }
        }
    }
</script>

<style scoped>

</style>
