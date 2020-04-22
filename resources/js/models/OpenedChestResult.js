import Item from "./Item";

export default class OpenedChestResult {

    constructor({gold, items = []}) {
        this.gold = gold;
        this.items = items.map(function (itemData) {
            return new Item(itemData);
        })
    }
}
