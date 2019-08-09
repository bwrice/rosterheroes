
export const viewBoxControlsMixin = {
    data: function() {
        return {
            originalViewBox: {
                pan_x: 0,
                pan_y: 0,
                zoom_x: 315,
                zoom_y: 240,
            },
            currentViewBox: {
                pan_x: 0,
                pan_y: 0,
                zoom_x: 315,
                zoom_y: 240,
            }
        }
    },
    computed: {

    },
    watch: {
        //
    },
    methods: {
        panUp() {
            this.currentViewBox.pan_y -= (.1 * this.currentViewBox.zoom_y);
        },
        panDown() {
            this.currentViewBox.pan_y += (.1 * this.currentViewBox.zoom_y);
        },
        panLeft() {
            this.currentViewBox.pan_x -= (.1 * this.currentViewBox.zoom_x);
        },
        panRight() {
            this.currentViewBox.pan_x += (.1 * this.currentViewBox.zoom_x);
        },
        restViewBox() {
            this.currentViewBox = _.cloneDeep(this.originalViewBox);
        },
        zoomIn() {
            /*
            By panning 10% but zooming 80% (instead of 90), we zoom the center of the SVG
             */
            this.panRight();
            this.panDown();
            this.currentViewBox.zoom_x *= .8;
            this.currentViewBox.zoom_y *= .8;
        },
        zoomOut() {
            /*
            We have to pan after we adjust the zoom to reverse zoomIn() effect
             */
            this.currentViewBox.zoom_x /= .8;
            this.currentViewBox.zoom_y /= .8;
            this.panLeft();
            this.panUp();
        },
        setViewBox(newValue) {
            this.originalViewBox = _.cloneDeep(newValue);
            this.currentViewBox = _.cloneDeep(newValue);
        }
    }
};
