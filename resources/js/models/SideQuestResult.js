import SideQuestSnapshot from "./SideQuestSnapshot";

export default class SideQuestResult {

    constructor({uuid, experienceRewarded = 0, favorRewarded = 0, sideQuestSnapshot = {}}) {
        this.uuid = uuid;
        this.experienceRewarded = experienceRewarded;
        this.favorRewarded = favorRewarded;
        this.sideQuestSnapshot = new SideQuestSnapshot(sideQuestSnapshot)
    }
}
