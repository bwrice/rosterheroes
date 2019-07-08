import Model from './Model'

export default class GamePlayer extends Model {

    constructor(gamePlayer) {
        super();
        this._salary = gamePlayer.salary;
    }

    get salary() {
        return this._salary;
    }
}