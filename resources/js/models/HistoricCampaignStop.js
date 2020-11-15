import SideQuestResult from "./SideQuestResult";

export default class HistoricCampaignStop {

    constructor({uuid, name = '', provinceUuid, sideQuestResults = []}) {
        this.uuid = uuid;
        this.name = name;
        this.provinceUuid = provinceUuid;
        this.sideQuestResults = sideQuestResults.map(result => new SideQuestResult(result));
    }
}
