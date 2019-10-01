import Item from "./Item";
import Slot from "./Slot";
import HasSlots from "./HasSlots";

export default class SlotTransaction {
    constructor({type, item, slots = [], hasSlots}) {
        this.type = type;
        this.item = item ? new Item(item) : new Item({});
        this.slots = slots.map(function (slot) {
            return new Slot(slot);
        });
        this.hasSlots = hasSlots ? new HasSlots(hasSlots) : new HasSlots({});
    }
}
