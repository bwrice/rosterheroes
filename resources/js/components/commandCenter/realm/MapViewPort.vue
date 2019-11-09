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

            mapSheet.addEventListener('mousedown', this.handleMouseDown);
            mapSheet.addEventListener('touchstart', this.handleTouchStart);
            mapSheet.addEventListener('mousemove', this.handleMouseMove);
            mapSheet.addEventListener('touchmove', this.handleTouchMove);
            window.document.addEventListener('mouseup', this.endPointerEvents);
            window.document.addEventListener('touchend', this.endPointerEvents);
        },
        data() {
            return {
                originalViewBox: new ViewBox({}),
                currentViewBox: new ViewBox({}),
                dragging: false,
                dragPosition: {
                    xCurrent: 0,
                    yCurrent: 0
                },
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
            // onDragged({ el, deltaX, deltaY, offsetX, offsetY, clientX, clientY, first, last }) {
            //     if (first) {
            //         this.isDragging = true;
            //         return;
            //     }
            //     if (last) {
            //         this.isDragging = false;
            //         return;
            //     }
            //     this.currentViewBox.pan(deltaX, deltaY);
            //     this.currentViewBox = _.cloneDeep(this.currentViewBox);
            // },
            handleMouseDown(e) {
                this.handleDragStart(e);
            },
            // handlePointerMoveEvent(e) {
            //     let touchPositions = this.touchPositions;
            //     let deltaX = e.clientX - touchPositions.xCurrent;
            //     let deltaY = e.clientY - touchPositions.yCurrent;
            //     if (! (isNaN(deltaX) || isNaN(deltaY))) {
            //         this.currentViewBox.pan(deltaX, deltaY);
            //         this.currentViewBox = _.cloneDeep(this.currentViewBox);
            //     } else {
            //         // console.log(e.clientX);
            //         // console.log(e.clientY);
            //         // console.log(touchPositions.xCurrent);
            //         // console.log(touchPositions.yCurrent);
            //         // let deltaX = e.clientX - touchPositions.xCurrent;
            //         // let deltaY = e.clientY - touchPositions.yCurrent;
            //         // console.log(e.clientX, e.clientX, deltaX, deltaY, touchPositions);
            //     }
            //     touchPositions.xCurrent = e.clientX;
            //     touchPositions.yCurrent = e.clientY;
            // },
            handleMouseMove(e) {
                if (! this.dragging) {
                    return;
                }
                e.preventDefault();
                this.handleDragMove(e);
            },
            handleTouchStart(e) {
                if (e.touches.length === 1) {
                    this.handleDragStart(e.touches[0]);
                }
            },
            handleTouchMove(e) {
                if (! this.dragging) {
                    return;
                }
                e.preventDefault();
                if (e.touches.length === 1) {
                    this.handleDragMove(e.touches[0])
                }
            },
            handlePointEndEvent(e) {
                this.handleDragEnd();
            },
            handleDragStart({clientX, clientY}) {
                this.dragging = true;
                this.dragPosition = {
                    xCurrent: clientX,
                    yCurrent: clientY
                }
            },
            handleDragMove({clientX, clientY}) {
                let dragPosition = this.dragPosition;
                let deltaX = clientX - dragPosition.xCurrent;
                let deltaY = clientY - dragPosition.yCurrent;
                this.currentViewBox.pan(deltaX, deltaY);
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
                this.dragPosition = {
                    xCurrent: clientX,
                    yCurrent: clientY
                }
            },
            endPointerEvents() {
                this.dragging = false;
            }
        }
    }
</script>

<style scoped>

</style>
