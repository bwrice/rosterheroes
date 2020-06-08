import SideQuestResult from "./SideQuestResult";

export default class CampaignStopResult {

    constructor({uuid, questName = '', sideQuestResults = []}) {
        this.uuid = uuid;
        this.questName = questName;
        this.sideQuestResults = sideQuestResults.map((sideQuestResult) => new SideQuestResult(sideQuestResult));
    }
}
