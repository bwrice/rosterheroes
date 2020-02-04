import Minion from "./Minion";

export default class SideQuest {

    constructor({uuid, slug, name, difficulty, minions = []}) {
        this.uuid = uuid;
        this.slug = slug;
        this.name = name;
        this.difficulty = difficulty;
        this.minions = minions.map(minion => new Minion(minion));
    }

}
