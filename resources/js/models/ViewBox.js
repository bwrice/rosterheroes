export default class ViewBox {

    constructor({panX = 0, panY = 0, zoomX = 100, zoomY = 100}) {
        this.panX = panX;
        this.panY = panY;
        this.zoomX = zoomX;
        this.zoomY = zoomY;
    }
}
