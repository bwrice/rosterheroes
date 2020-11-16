import Attack from "./Attack";
import BaseMinion from "./BaseMinion";

export default class Minion extends BaseMinion {

    constructor({
        slug,
        attacks = [],
        ...rest
    }) {
        super(rest);
        this.slug = slug;
        this.attacks = attacks.map(attack => new Attack(attack));
    }

}
