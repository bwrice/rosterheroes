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
    import Hammer from 'hammerjs';

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
            mapSheet.onwheel = _.throttle((e) => this.handleMouseWheel(e), 50);

            let hammer = new Hammer(mapSheet);
            hammer.add( new Hammer.Pan({ direction: Hammer.DIRECTION_ALL, threshold: 0 }) );
            hammer.on("pan", this.handleDrag);
            hammer.get("pinch").set({ enable: true });
            hammer.on("pinch", this.handlePinch, 200);
        },
        data() {
            return {
                originalViewBox: new ViewBox({}),
                currentViewBox: new ViewBox({}),
                dragging: false,
                dragPosition: {
                    currentDeltaX: 0,
                    currentDeltaY: 0
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
            handleDrag(ev) {
                if ( ! this.dragging ) {
                    this.dragging = true;
                }

                let changeInDeltaX = ev.deltaX - this.dragPosition.currentDeltaX;
                let changeInDeltaY = ev.deltaY - this.dragPosition.currentDeltaY;
                this.currentViewBox.pan(changeInDeltaX, changeInDeltaY);
                this.currentViewBox = _.cloneDeep(this.currentViewBox);

                this.dragPosition.currentDeltaX = ev.deltaX;
                this.dragPosition.currentDeltaY = ev.deltaY;

                if (ev.isFinal) {
                    this.dragging = false;
                    this.dragPosition = {
                        currentDeltaX: 0,
                        currentDeltaY: 0
                    }
                }
            },
            handlePinch(ev) {
                if (ev.scale > 1) {
                    this.currentViewBox.zoomIn();
                } else {
                    this.currentViewBox.zoomOut();
                }
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            handleMouseWheel(ev) {
                if (ev.deltaY > 0) {
                    // zoom twice so it's slightly faster
                    this.currentViewBox.zoomIn();
                    this.currentViewBox.zoomIn();
                }

                if (ev.deltaY < 0 ) {
                    this.currentViewBox.zoomOut();
                    this.currentViewBox.zoomOut();
                }
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
                return false;
            }
        }
    }
</script>

<style scoped>

</style>
