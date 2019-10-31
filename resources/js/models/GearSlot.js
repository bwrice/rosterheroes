import Item from "./Item";

export default class GearSlot {

    constructor({type = '', item}) {
        this.type = type;
        this.item = item ? new Item(item): null;
    }
}
