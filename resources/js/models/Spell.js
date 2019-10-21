export default class Spell {

    constructor({id, name = '', manaCost, measurableBoosts = []}) {
        this.id = id;
        this.name = name;
        this.manaCost = manaCost;
        this.measurableBoosts = measurableBoosts;
    }
}
