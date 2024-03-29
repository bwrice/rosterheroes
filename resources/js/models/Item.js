import Attack from "./Attack";
import ItemType from "./ItemType";
import ItemClass from "./ItemClass";
import Material from "./Material";
import Enchantment from "./Enchantment";
import * as mathHelpers from "../helpers/mathHelpers";

export default class Item {

    constructor(
        {
            name = '',
            uuid,
            attacks = [],
            enchantments = [],
            itemType,
            itemClass,
            material,
            weight,
            protection,
            blockChance,
            value,
            transaction,
            shopPrice = 0,
            enchantmentQuality
        }) {
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
        this.enchantmentQuality = enchantmentQuality ? enchantmentQuality : {name: 'Standard', value: 0};
    }

    get shopPriceTruncated() {

        let price = this.shopPrice;
        if (! price) {
            return 0;
        }
        return mathHelpers.shortenedNotation(price);
    }
}
