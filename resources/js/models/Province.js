import ViewBox from "./ViewBox";

export default class Province {

    constructor({uuid, name = '', slug = '', color = '', travelCost = 0, viewBox, vectorPaths = '', continentID = 0, territoryID = 0, borderUuids = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.color = color;
        this.travelCost = travelCost;
        this.viewBox = viewBox ? new ViewBox(viewBox) : new ViewBox({});
        this.vectorPaths = vectorPaths;
        this.continentID = continentID;
        this.territoryID = territoryID;
        this.borderUuids = borderUuids;
    }

    goToRoute(router, route) {
        router.push(this.getRoute(route));
    }

    getRoute(route) {
        return {
            name: 'province-map',
            params: {
                squadSlug: route.params.squadSlug,
                provinceSlug: this.slug
            }
        }
    }
}
