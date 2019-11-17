
export default class CampaignStop {

    constructor({uuid, name, questID, provinceID, campaignID, skirmishUuids = []}) {
        this.uuid = uuid;
        this.name = name;
        this.questID = questID;
        this.provinceID = provinceID;
        this.campaignID = campaignID;
        this.skirmishUuids = skirmishUuids;
    }
}
