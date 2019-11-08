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
                >
                    <slot>
                        <!-- Default Slot: ProvinceVector components slotted here -->
                    </slot>
                </svg>
            </v-sheet>
        </v-col>
        <v-col cols="12" style="margin-top: -32px">
            <v-row no-gutters justify="end" class="mx-1">
                <v-btn small @click="resetViewPort">
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
            let self = this;

            let mapSheet = document.getElementById('map-sheet');
            mapSheet.onwheel = _.throttle(function(e) {
                if (e.deltaY > 0) {
                    self.currentViewBox.zoomIn();
                }

                if (e.deltaY < 0 ) {
                    self.currentViewBox.zoomOut();
                }
                self.currentViewBox = _.cloneDeep(self.currentViewBox);
                return false;
            }, 125);

            mapSheet.addEventListener('mousedown', this.handlePointerStartEvent);
            mapSheet.addEventListener('mousemove', this.handlePointerMoveEvent);
            window.document.addEventListener('mouseup', this.handlePointEndEvent);
        },
        data() {
            return {
                originalViewBox: new ViewBox({}),
                currentViewBox: new ViewBox({}),
                touchPositions: {
                    touched: false,
                    xStart: 0,
                    yStart: 0,
                    xCurrent: 0,
                    yCurrent: 0,
                }
            }
        },
        watch: {
            viewBox: function() {
                this.initializeViewBox();
            }
        },
        computed: {
            viewBoxString() {
                return this.currentViewBox.panX + ' ' + this.currentViewBox.panY + ' ' + this.currentViewBox.zoomX + ' ' + this.currentViewBox.zoomY;
            }
        },
        methods: {
            initializeViewBox() {
                this.originalViewBox =  _.cloneDeep(this.viewBox);
                this.currentViewBox = _.cloneDeep(this.viewBox);
            },
            resetViewPort() {
                this.currentViewBox = _.cloneDeep(this.originalViewBox);
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
                this.currentViewBox.pan(deltaX, deltaY);
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            handlePointerStartEvent(e) {
                let touchPositions = this.touchPositions;
                touchPositions.touched = true;
                touchPositions.xCurrent = e.clientX;
                touchPositions.yCurrent = e.clientY;
            },
            handlePointerMoveEvent(e) {
                let touchPositions = this.touchPositions;
                if (! touchPositions.touched) {
                    return;
                }
                e.preventDefault();
                let deltaX = e.clientX - touchPositions.xCurrent;
                let deltaY = e.clientY - touchPositions.yCurrent;
                this.currentViewBox.pan(deltaX, deltaY);
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
                touchPositions.xCurrent = e.clientX;
                touchPositions.yCurrent = e.clientY;
            },
            handlePointEndEvent(e) {
                this.touchPositions.touched = false;
            }
        }
    }
</script>

<style scoped>

</style>
