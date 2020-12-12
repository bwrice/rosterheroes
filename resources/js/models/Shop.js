import Item from "./Item";

export default class Shop {

    constructor({uuid, name = '', slug = '', tier = 1, items = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.tier = tier;
        this.items = items.map((item) => new Item(item));
    }

    goldForItem(itemToSell) {
        return Math.floor(itemToSell.value * 0.6);
    }

    goldForItems(itemsToSell) {
        if (itemsToSell.length > 0) {
            let self = this;
            return itemsToSell.reduce(function (total, itemToSell) {
                return total + self.goldForItem(itemToSell);
            }, 0)
        }
        return 0;
    }
}
