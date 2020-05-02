import CompactQuest from "./compact/CompactQuest";

export default class CampaignStop {

    constructor({uuid, name, questUuid, provinceUuid, campaignUuid, sideQuestUuids = []}, compactQuest) {
        this.uuid = uuid;
        this.name = name;
        this.questUuid = questUuid;
        this.provinceUuid = provinceUuid;
        this.campaignUuid = campaignUuid;
        this.sideQuestUuids = sideQuestUuids;
        this.compactQuest = compactQuest ? new CompactQuest(compactQuest) : new CompactQuest({});
    }
}
