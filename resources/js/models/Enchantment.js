import MeasurableBoost from "./MeasurableBoost";

export default class Enchantment {

    constructor({name = '', measurableBoosts = []}) {
        this.name = name;
        this.measurableBoosts = measurableBoosts.map(function (boost) {
            return new MeasurableBoost(boost);
        });
    }
}
