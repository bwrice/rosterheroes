import Skirmish from "./Skirmish";
import Titan from "./Titan";
import Minion from "./Minion";

export default class Quest {

    constructor({uuid, name = '', slug = '', level, provinceID, percent, skirmishes = [], titans = [], minions = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.level = level;
        this.provinceID = provinceID;
        this.percent = percent;
        this.skirmishes = skirmishes.map(skirmish => new Skirmish(skirmish));
        this.titans = titans.map(titan => new Titan(titan));
        this.minions = minions.map(minion => new Minion(minion));
    }

    get percentComplete() {
        return 100 - this.percent;
    }

}
