import Item from "./Item";

export default class OpenedChestResult {

    constructor({gold, items = []}) {
        this.gold = gold;
        this.items = items.map(function (itemData) {
            return new Item(itemData);
        })
    }

    get itemsMovedToMobileStorage() {
        return this.itemsMovedByHasItemsType('squads')
    }

    get itemsMovedToStash() {
        return this.itemsMovedByHasItemsType('stashes')
    }

    get itemsMovedToResidence() {
        return this.itemsMovedByHasItemsType('residences')
    }

    itemsMovedByHasItemsType(hasItemsType) {
        return this.items.filter(function (item) {
            if (item.transaction.to) {
                return item.transaction.to.type === hasItemsType;
            }
            return false;
        });
    }
}
