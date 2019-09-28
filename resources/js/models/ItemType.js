import ItemBase from "./ItemBase";

export default class ItemType {

    constructor({name = '', grade = 1, itemBase}) {
        this.name = name;
        this.grade = grade;
        this.itemBase = itemBase ? new ItemBase(itemBase) : new ItemBase({});
    }
}
