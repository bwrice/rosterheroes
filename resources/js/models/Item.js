import Attack from "./Attack";
import ItemType from "./ItemType";

export default class Item {

    constructor({name = '', attacks = [], itemType}) {
        this.name = name;
        this.attacks = attacks.map(function (attack) {
            return new Attack(attack);
        });
        this.itemType = itemType ? new ItemType(itemType) : new ItemType({});
    }
}
