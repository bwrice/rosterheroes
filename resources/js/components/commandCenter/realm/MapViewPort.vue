<template>
    <v-row no-gutters>
        <v-col cols="12">
            <v-sheet
                id="map-sheet"
                :tile="tile"
                :color="oceanColor"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     version="1.1"
                     display="block"
                     :viewBox="viewBoxString"
                     v-dragged="onDragged"
                >
                    <slot>
                        <!-- Default Slot: ProvinceVector components slotted here -->
                    </slot>
                </svg>
            </v-sheet>
        </v-col>
        <v-col cols="12" style="margin-top: -32px">
            <v-row no-gutters justify="end" class="mx-1">
                <v-btn small>
                    reset
                </v-btn>
            </v-row>
        </v-col>
    </v-row>
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
            },
            tile: {
                type: Boolean,
                default: true
            },
            requiresRealm: {
                type: Boolean,
                default: true
            }
        },
        created() {
            this.initializeViewBox();
        },
        mounted() {
            let mapSheet = document.getElementById('map-sheet');
            mapSheet.addEventListener('touchmove', function(e) {
                e.preventDefault();
            }, false);
        },
        data() {
            return {
                currentPanX: 0,
                currentPanY: 0,
                currentZoomX: 315,
                currentZoomY: 240
            }
        },
        computed: {
            viewBoxString() {
                return this.currentPanX + ' ' + this.currentPanY+ ' ' + this.currentZoomX + ' ' + this.currentZoomY;
            }
        },
        methods: {
            initializeViewBox() {
                this.currentPanX = this.viewBox.pan_x;
                this.currentPanY = this.viewBox.pan_y;
                this.currentZoomX = this.viewBox.zoom_x;
                this.currentZoomY = this.viewBox.zoom_y;
            },

            onDragged({ el, deltaX, deltaY, offsetX, offsetY, clientX, clientY, first, last }) {
                if (first) {
                    this.isDragging = true;
                    return
                }
                if (last) {
                    this.isDragging = false;
                    return;
                }
                this.currentPanX -= deltaX;
                this.currentPanY -= deltaY;
            }
        }
    }
</script>

<style scoped>

</style>
