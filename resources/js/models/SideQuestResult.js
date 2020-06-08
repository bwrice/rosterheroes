import SideQuest from "./SideQuest";

export default class SideQuestResult {

    constructor({uuid, sideQuest = {}, experienceRewarded = 0, favorRewarded = 0}) {
        this.uuid = uuid;
        this.sideQuest = new SideQuest(sideQuest);
        this.experienceRewarded = experienceRewarded;
        this.favorRewarded = favorRewarded;
    }
}
