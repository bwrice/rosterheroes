import MaterialType from "./MaterialType";
import MeasurableType from "./MeasurableType";

export default class Measurable {

    constructor({name = '', uuid, measurableType, amountRaised, costToRaise, preBuffedAmount, buffedAmount, spentOnRaising}) {
        this.name = name;
        this.uuid = uuid;
        this.measurableType = measurableType ? new MeasurableType(measurableType) : new MeasurableType({});
        this.amountRaised = amountRaised;
        this.costToRaise = costToRaise;
        this._buffedAmount = buffedAmount;
        this._preBuffedAmount = preBuffedAmount;
        this.spentOnRaising = spentOnRaising;
    }

    get buffedAmount() {
        return this._buffedAmount;
    }

    get preBuffedAmount() {
        return this._preBuffedAmount;
    }
}
