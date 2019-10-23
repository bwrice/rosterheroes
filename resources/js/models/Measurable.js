
export default class Measurable {

    constructor({name = '', uuid, measurableTypeID = 0, amountRaised, costToRaise, preBuffedAmount, buffedAmount, currentAmount, spentOnRaising}) {
        this.name = name;
        this.uuid = uuid;
        this.measurableTypeID = measurableTypeID;
        this.amountRaised = amountRaised;
        this.costToRaise = costToRaise;
        this._buffedAmount = buffedAmount;
        this._preBuffedAmount = preBuffedAmount;
        this.currentAmount = currentAmount;
        this.spentOnRaising = spentOnRaising;
    }

    get buffedAmount() {
        return this._buffedAmount;
    }

    get preBuffedAmount() {
        return this._preBuffedAmount;
    }
}
