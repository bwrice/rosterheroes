import Minion from "./Minion";

export default class Skirmish {

    constructor({uuid, slug, name, minions = []}) {
        this.uuid = uuid;
        this.slug = slug;
        this.name = name;
        this.minions = minions.map(minion => new Minion(minion));
    }

}
