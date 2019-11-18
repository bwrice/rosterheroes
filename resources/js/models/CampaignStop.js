
export default class CampaignStop {

    constructor({uuid, name, questUuid, provinceUuid, campaignUuid, skirmishUuids = []}) {
        this.uuid = uuid;
        this.name = name;
        this.questUuid = questUuid;
        this.provinceUuid = provinceUuid;
        this.campaignUuid = campaignUuid;
        this.skirmishUuids = skirmishUuids;
    }
}
