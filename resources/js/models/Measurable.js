import MaterialType from "./MaterialType";
import MeasurableType from "./MeasurableType";

export default class Measurable {

    constructor({name = '', uuid, measurableType, amountRaised, costToRaise, currentAmount, spentOnRaising}) {
        this.name = name;
        this.uuid = uuid;
        this.measurableType = measurableType ? new MeasurableType(measurableType) : new MeasurableType({});
        this.amountRaised = amountRaised;
        this.costToRaise = costToRaise;
        this.currentAmount = currentAmount;
        this.spentOnRaising = spentOnRaising;
    }
}
