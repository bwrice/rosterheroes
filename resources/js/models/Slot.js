import Item from "./Item";
import SlotType from "./SlotType";

export default class Slot {

    constructor({item, slotType = {}} = {}) {
        this.item = item ? new Item(item) : null;
        this.slotType = new SlotType(slotType);
    }

    empty() {
        return ! this.item;
    }
}
