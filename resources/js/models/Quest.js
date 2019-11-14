import Skirmish from "./Skirmish";
import Titan from "./Titan";
import Minion from "./Minion";

export default class Quest {

    constructor({uuid, name = '', level, provinceID, skirmishes = [], titans = [], minions = []}) {
        this.uuid = uuid;
        this.name = name;
        this.level = level;
        this.provinceID = provinceID;
        this.skirmishes = skirmishes.map(skirmish => new Skirmish(skirmish));
        this.titans = titans.map(titan => new Titan(titan));
        this.minions = minions.map(minion => new Minion(minion));
    }

}
