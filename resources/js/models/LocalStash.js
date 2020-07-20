import Item from "./Item";

export default class LocalStash {

    constructor({uuid, provinceUuid, items = []}) {
        this.uuid = uuid;
        this.provinceUuid = provinceUuid;
        this.items = items.map(function (itemData) {
            return new Item(itemData);
        });
    }
}
