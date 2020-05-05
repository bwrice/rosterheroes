
import CompactStash from "./compact/CompactStash";

export default class ExploredProvince {

    constructor({provinceUuid, provinceSlug, squadStash, compactQuests = []}) {
        this.provinceUuid = provinceUuid;
        this.provinceSlug = provinceSlug;
        this.squadStash = squadStash ? new CompactStash(squadStash) : new CompactStash({});
        this.compactQuests = compactQuests;
    }
}
