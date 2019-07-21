import Model from './Model'

export default class PlayerSpirit extends Model {

    constructor(playerSpirit) {
        super();
        this._salary = playerSpirit.essence_cost;
    }

    get essenceCost() {
        return this._essence_cost;
    }
}