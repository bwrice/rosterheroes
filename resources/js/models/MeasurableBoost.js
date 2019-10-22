
export default class MeasurableBoost {

    constructor({measurableTypeID = 0, boostLevel, boostAmount, description}) {
        this.measurableTypeID = measurableTypeID;
        this.boostLevel = boostLevel;
        this.boostAmount = boostAmount;
        this.description = description;
    }
}
