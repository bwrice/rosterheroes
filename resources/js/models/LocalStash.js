import Item from "./Item";

export default class LocalStash {

    constructor({uuid, items = []}) {
        this.uuid = uuid;
        this.items = items.map(function (itemData) {
            return new Item(itemData);
        });
    }
}
