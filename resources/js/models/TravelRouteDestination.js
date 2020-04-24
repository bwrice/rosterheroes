import Province from "./Province";

export default class TravelRouteDestination {

    constructor({cost, province}) {
        this.cost = cost;
        this.province = province ? new Province(province) : new Province({});
    }
}
