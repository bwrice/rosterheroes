
import CompactQuest from "./compact/CompactQuest";

export default class ExploredProvince {

    constructor({provinceUuid, provinceSlug, quests = []}) {
        this.provinceUuid = provinceUuid;
        this.provinceSlug = provinceSlug;
        this.quests = quests.map(function (questData) {
            return new CompactQuest(questData);
        });
    }
}
