import GamePlayer from './gamePlayer';

export default class Hero {

    constructor(hero) {
        this._uuid = hero.uuid;
        this._name = hero.name;
        this._gamePlayer = hero.gamePlayer ? new GamePlayer(hero.gamePlayer) : null;
    }

    get uuid() {
        return this._uuid;
    }

    get name() {
        return this._name;
    }

    get gamePlayer() {
        return this._gamePlayer;
    }

    get salaryUsed() {
        if (this._gamePlayer) {
            return this._gamePlayer.salary;
        } else  {
            return 0;
        }
    }
}