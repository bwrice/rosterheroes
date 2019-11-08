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

            // mapSheet.onwheel = function (e) {
            //     _.debounce(function() {
            //         console.log(e);
            //     }, {
            //         leading: true
            //     });
            //     return false;
            // };

            mapSheet.onwheel = _.throttle(function(e) {
                console.log(e);
                return false;
            }, 100);

            // mapSheet.addEventListener('wheel scroll touchmove mousewheel', function(e) {
            //     e.preventDefault();
            //     e.stopPropagation();
            // }, false);

            // mapSheet.addEventListener("scroll", function(e) {
            //     console.log("SCROLLING");
            //     e.preventDefault();
            // });
        },
        data() {
            return {
                originalViewBox: new ViewBox({}),
                currentViewBox: new ViewBox({})
            }
        },
        computed: {
            viewBoxString() {
                return this.currentViewBox.panX + ' ' + this.currentViewBox.panY + ' ' + this.currentViewBox.zoomX + ' ' + this.currentViewBox.zoomY;
            }
        },
        methods: {
            initializeViewBox() {
                this.originalViewBox = this.viewBox;
                this.currentViewBox = this.viewBox;
            },

            onDragged({ el, deltaX, deltaY, offsetX, offsetY, clientX, clientY, first, last }) {
                if (first) {
                    this.isDragging = true;
                    return;
                }
                if (last) {
                    this.isDragging = false;
                    return;
                }
                this.currentPanX -= deltaX;
                this.currentPanY -= deltaY;
            },
            onScrolled(e) {
                e.preventDefault();
                console.log(e);
            }
        }
    }
</script>

<style scoped>

</style>
