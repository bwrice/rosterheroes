<template>
    <v-sheet color="#3a474a">
        <MapViewPort :view-box="currentViewBox">
            <slot>
                <!-- Default Slot: Passed down to MapViewPort default slot -->
            </slot>
        </MapViewPort>
        <v-row no-gutters class="py-2">
            <v-col cols="12" md="8" offset-md="2" lg="6" offset-lg="3">
                <v-row no-gutters justify="center" align="center">
                    <v-col cols="3">
                        <v-row no-gutters class="flex-column" align="center">
                            <v-btn small outlined color="#bababa" @click="zoomIn()" class="my-1">
                                <v-icon dark>zoom_in</v-icon>
                            </v-btn>
                            <v-btn small outlined color="#bababa" @click="zoomOut()" class="my-1">
                                <v-icon dark>zoom_out</v-icon>
                            </v-btn>
                        </v-row>
                    </v-col>
                    <v-col cols="4">
                        <v-row justify="center">
                            <v-btn outlined color="#bababa" @click="resetViewPort">
                                reset
                            </v-btn>
                        </v-row>
                    </v-col>
                    <v-col cols="5">
                        <v-row no-gutters justify="center">
                            <v-btn small outlined color="#bababa" @click="panUp">
                                <v-icon dark>arrow_drop_up</v-icon>
                            </v-btn>
                        </v-row>
                        <v-row no-gutters justify="center">
                            <v-btn small outlined color="#bababa" @click="panLeft">
                                <v-icon dark>arrow_left</v-icon>
                            </v-btn>
                            <v-btn small outlined color="#bababa" @click="panRight">
                                <v-icon dark>arrow_right</v-icon>
                            </v-btn>
                        </v-row>
                        <v-row no-gutters justify="center">
                            <v-btn small outlined color="#bababa" @click="panDown">
                                <v-icon dark>arrow_drop_down</v-icon>
                            </v-btn>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>

    import ViewBox from "../../../models/ViewBox";
    import Hammer from 'hammerjs';
    import MapViewPort from "./MapViewPort";

    export default {
        name: "MapViewPortWithControls",
        components: {MapViewPort},
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
                    this.zoomOut(2);
                }

                if (ev.deltaY < 0 ) {
                    this.zoomIn(2);
                }
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
                return false;
            },
            zoomIn(amount = 1) {
                for(let i = 1; i <= amount; i++) {
                    this.currentViewBox.zoomIn();
                }
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            zoomOut(amount = 1) {
                for(let i = 1; i <= amount; i++) {
                    this.currentViewBox.zoomOut();
                }
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            panUp() {
                this.currentViewBox.panUp();
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            panDown() {
                this.currentViewBox.panDown();
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            panLeft() {
                this.currentViewBox.panLeft();
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            },
            panRight() {
                this.currentViewBox.panRight();
                this.currentViewBox = _.cloneDeep(this.currentViewBox);
            }
        }
    }
</script>

<style scoped>

</style>
