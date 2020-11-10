import Week from "./Week";
import HeroSnapshot from "./HeroSnapshot";

export default class SquadSnapshot {

    constructor({
        uuid,
        squadRankID = 0,
        week = {},
        experience,
        heroSnapshots = []
    }) {
        this.uuid = uuid;
        this.squadRankID = squadRankID;
        this.week = new Week(week);
        this.experience = experience;
        this.heroSnapshots = heroSnapshots.map(snapshot => new HeroSnapshot(snapshot));
    }
}
