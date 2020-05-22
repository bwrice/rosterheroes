export default class StatMeasurableBonus {

    constructor({statTypeID, measurableTypeID, percentModifier}) {
        this.statTypeID = statTypeID;
        this.measurableTypeID = measurableTypeID;
        this.percentModifier = percentModifier;
    }
}
