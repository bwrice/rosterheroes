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

            mapSheet.addEventListener('mousedown', function(e) {
                self.touchPositions.touched = true;
                self.touchPositions.xStart = self.touchPositions.xCurrent = e.clientX;
                self.touchPositions.yStart = self.touchPositions.yCurrent = e.clientY;
            });

            mapSheet.addEventListener('mousemove', function(e) {
                if (! self.touchPositions.touched) {
                    return;
                }
                e.preventDefault();
                console.log(e.clientX, e.clientY, self.touchPositions.xCurrent, self.touchPositions.yCurrent);
                let deltaX = e.clientX - self.touchPositions.xCurrent;
                let deltaY = e.clientY - self.touchPositions.yCurrent;
                self.currentViewBox.pan(deltaX, deltaY);
                self.currentViewBox = _.cloneDeep(self.currentViewBox);
                self.touchPositions.xCurrent = e.clientX;
                self.touchPositions.yCurrent = e.clientY;
            })

            // mapSheet.addEventListener('touchmove', function(e) {
            //     console.log(e);
            //     e.preventDefault();
            // }, false);
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
            }
        }
    }
</script>

<style scoped>

</style>
