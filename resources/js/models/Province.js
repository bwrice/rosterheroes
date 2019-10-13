
export default class Province {

    constructor({uuid, name = '', slug = '', color = '', travelCost = 0, viewBox = {}, vectorPaths = '', continentID = 0, territoryID = 0, borderUuids = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.color = color;
        this.travelCost = travelCost;
        this.viewBox = viewBox;
        this.vectorPaths = vectorPaths;
        this.continentID = continentID;
        this.territoryID = territoryID;
        this.borderUuids = borderUuids;
    }
}
