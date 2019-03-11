export default class GamePlayer {

    constructor(gamePlayer) {
        this._salary = gamePlayer.salary;
    }

    get salary() {
        return this._salary;
    }
}