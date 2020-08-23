
export default class MapProvince {

    constructor({provinceUuid, provinceSlug, questsCount, squadsCount, availableMerchants = []}) {
        this.provinceUuid = provinceUuid;
        this.provinceSlug = provinceSlug;
        this.questsCount = questsCount;
        this.squadsCount = squadsCount;
        this.availableMerchants = availableMerchants;
    }
}
