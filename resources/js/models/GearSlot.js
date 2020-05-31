import Item from "./Item";

export default class GearSlot {

    constructor({type = '', item, priority = 0}) {
        this.type = type;
        this.item = item ? new Item(item): null;
        this.priority = priority;
    }
}
