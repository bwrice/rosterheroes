import MeasurableType from "./MeasurableType";

export default class MeasurableBoost {

    constructor({measurableType, boost_level}) {
        this.measurableType = measurableType ?
            new MeasurableType(measurableType) : new MeasurableType({});
        this.boostLevel = boost_level;
    }
}
