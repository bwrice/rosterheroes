export default class ViewBox {

    constructor({panX = 0, panY = 0, zoomX = 315, zoomY = 240}) {
        this.panX = panX;
        this.panY = panY;
        this.zoomX = zoomX;
        this.zoomY = zoomY;
    }

    pan(deltaX, deltaY) {
        // use zoomX for both calculations so dragging is same for both x and y axis
        this.panX -= deltaX/(300/this.zoomX);
        this.panY -= deltaY/(300/this.zoomX);
    }

    panUp() {
        this.panY -= (.1 * this.zoomY);
    }

    panDown() {
        this.panY += (.1 * this.zoomY);
    }

    panLeft() {
        this.panX -= (.1 * this.zoomX);
    }

    panRight() {
        this.panX += (.1 * this.zoomX);
    }

    zoomIn() {
        /*
        By panning 10% but zooming 80% (instead of 90), we zoom the center of the SVG
         */
        this.panRight();
        this.panDown();
        this.zoomX *= .8;
        this.zoomY *= .8;
    }

    zoomOut() {
        /*
        We have to pan after we adjust the zoom to reverse zoomIn() effect
         */
        this.zoomX /= .8;
        this.zoomY /= .8;
        this.panLeft();
        this.panUp();
    }
}
