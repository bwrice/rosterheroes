import Item from "./Item";

export default class GearSlot {

    constructor({type = '', item, priority = 1}) {
        this.type = type;
        this.item = item ? new Item(item): null;
        this.priority = priority;
    }

    filterItemsAvailableForHero(items, hero) {

        let type = this.type;
        if (this.priority === 1) {
            return items.filter(function (item) {
                let slotTypeNames = item.itemType.itemBase.slotTypeNames;
                return slotTypeNames.includes(type)
            })
        }
        return [];
    }
}
