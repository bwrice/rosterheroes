
export default class HeroRace {

    constructor({id, name = '', svg = '', positionIDs = []}) {
        this.id = id;
        this.name = name;
        this.svg = svg;
        this.positionIDs = positionIDs;
    }
}
