import CampaignStop from "./CampaignStop";

export default class Campaign {

    constructor({uuid, squadID, continentID, campaignStops = []}) {
        this.uuid = uuid;
        this.squadID = squadID;
        this.continentID = continentID;
        this.campaignStops = campaignStops.map(campaignStop => new CampaignStop(campaignStop));
    }
}
