import Province from "./Province";

export default class CurrentLocation extends Province {

    constructor({uuid, name = '', slug = '', color = '', travelCost = 0, viewBox, vectorPaths = '', continentID = 0, territoryID = 0, borderUuids = []}) {

        super({uuid, name, slug, color, travelCost, viewBox, vectorPaths, continentID, territoryID, borderUuids});

    }

}
