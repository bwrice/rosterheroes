import Province from "./Province";

export default class TravelRouteDestination {

    constructor({cost, province = new Province({})}) {
        this.cost = cost;
        this.province = province;
    }
}
