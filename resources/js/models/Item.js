import Attack from "./Attack";
import ItemType from "./ItemType";
import ItemClass from "./ItemClass";
import Material from "./Material";
import Enchantment from "./Enchantment";

export default class Item {

    constructor({name = '', uuid, attacks = [], enchantments = [], itemType, itemClass, material, weight, protection, blockChance, value, transaction, shopPrice = 0}) {
        this.name = name;
        this.uuid = uuid;
        this.weight = weight;
        this.protection = protection;
        this.blockChance = blockChance;
        this.value = value;
        this.attacks = attacks.map(function (attack) {
            return new Attack(attack);
        });
        this.enchantments = enchantments.map(function (enchantment) {
            return new Enchantment(enchantment);
        });
        this.itemType = itemType ? new ItemType(itemType) : new ItemType({});
        this.itemClass = itemClass ? new ItemClass(itemClass) : new ItemClass({});
        this.material = material ? new Material(material) : new Material({});
        this.transaction = transaction;
        this.shopPrice = shopPrice;
    }
}
