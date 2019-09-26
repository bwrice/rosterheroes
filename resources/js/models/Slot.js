import Item from "./Item";
import SlotType from "./SlotType";

export default class Slot {

    constructor({uuid, item, slotType}) {
        this.uuid = uuid;
        this.item = item ? new Item(item) : null;
        this.slotType = slotType ? new SlotType(slotType) : new SlotType({});
    }

    empty() {
        return ! this.item;
    }
}
