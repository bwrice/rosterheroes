export default class OpenedChestResult {

    constructor({gold, items = []}) {
        this.gold = gold;
        this.items = items;
    }
}
