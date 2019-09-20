import Attack from "./Attack";
import ItemType from "./ItemType";
import ItemClass from "./ItemClass";
import Material from "./Material";

export default class Item {

    constructor({name = '', attacks = [], itemType, itemClass, material, burden, protection, blockChance, value}) {
        this.name = name;
        this.burden = burden;
        this.protection = protection;
        this.blockChance = blockChance;
        this.value = value;
        this.attacks = attacks.map(function (attack) {
            return new Attack(attack);
        });
        this.itemType = itemType ? new ItemType(itemType) : new ItemType({});
        this.itemClass = itemClass ? new ItemClass(itemClass) : new ItemClass({});
        this.material = material ? new Material(material) : new Material({});
    }
}
