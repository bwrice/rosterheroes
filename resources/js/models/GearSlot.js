import Item from "./Item";

export default class GearSlot {

    constructor({type = '', item, priority = 1}) {
        this.type = type;
        this.item = item ? new Item(item): null;
        this.priority = priority;
    }
}
