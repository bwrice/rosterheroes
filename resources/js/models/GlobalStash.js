
export default class GlobalStash {

    constructor({uuid, provinceUuid, itemsCount}) {
        this.uuid = uuid;
        this.provinceUuid = provinceUuid;
        this.itemsCount = itemsCount;
    }

    increaseItemsCount() {
        this.itemsCount++;
    }

    decreaseItemsCount() {
        this.itemsCount--;
    }
}
