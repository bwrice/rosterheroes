import SideQuest from "./SideQuest";
import Titan from "./Titan";
import Minion from "./Minion";

export default class Quest {

    constructor({uuid, name = '', slug = '', level, provinceID, percent, sideQuests = [], titans = [], minions = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.level = level;
        this.provinceID = provinceID;
        this.percent = percent;
        this.sideQuests = sideQuests.map(sideQuest => new SideQuest(sideQuest));
        this.titans = titans.map(titan => new Titan(titan));
        this.minions = minions.map(minion => new Minion(minion));
    }

    get percentComplete() {
        return 100 - this.percent;
    }

}
