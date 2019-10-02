import SlotType from "./SlotType";

export default class ItemBase {

    constructor({name = '', slotTypeNames = []}) {
        this.name = name;
        this.slotTypeNames = slotTypeNames;
    }
}
