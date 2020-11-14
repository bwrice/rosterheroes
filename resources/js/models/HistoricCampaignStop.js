import SideQuestResult from "./SideQuestResult";

export default class HistoricCampaignStop {

    constructor({uuid, provinceUuid, sideQuestResults = []}) {
        this.uuid = uuid;
        this.provinceUuid = provinceUuid;
        this.sideQuestResults = sideQuestResults.map(result => new SideQuestResult(result));
    }
}
