import CampaignStopResult from "./CampaignStopResult";

export default class HistoricCampaign {

    constructor({uuid, description = '', campaignStopResults = []}) {
        this.uuid = uuid;
        this.description = description;
        this.campaignStopResults = campaignStopResults.map((stopResult) => new CampaignStopResult(stopResult));
    }
}
