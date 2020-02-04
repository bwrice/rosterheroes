
export default class CampaignStop {

    constructor({uuid, name, questUuid, provinceUuid, campaignUuid, sideQuestUuids = []}) {
        this.uuid = uuid;
        this.name = name;
        this.questUuid = questUuid;
        this.provinceUuid = provinceUuid;
        this.campaignUuid = campaignUuid;
        this.sideQuestUuids = sideQuestUuids;
    }
}
