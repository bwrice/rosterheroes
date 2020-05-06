
import CompactQuest from "./compact/CompactQuest";

export default class ExploredProvince {

    constructor({provinceUuid, provinceSlug, compactQuests = []}) {
        this.provinceUuid = provinceUuid;
        this.provinceSlug = provinceSlug;
        this.compactQuests = compactQuests.map(function (quest) {
            return new CompactQuest(quest);
        });
    }
}
