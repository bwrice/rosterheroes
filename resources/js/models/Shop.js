import Item from "./Item";

export default class Shop {

    constructor({uuid, name = '', slug = '', tier = 1, items = []}) {
        this.uuid = uuid;
        this.name = name;
        this.slug = slug;
        this.tier = tier;
        this.items = items.map((item) => new Item(item));
    }
}
