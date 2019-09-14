import Attack from "./Attack";

export default class Item {

    constructor({name = '', attacks = []}) {
        this.name = name;
        this.attacks = attacks.map(function (attack) {
            return new Attack(attack);
        })
    }
}
