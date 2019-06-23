import GamePlayer from './gamePlayer';

export default class Hero {

    constructor(hero) {
        this._uuid = hero.uuid;
        this._name = hero.name;
        this._weeklyGamePlayer = hero.weeklyGamePlayer ? new GamePlayer(hero.weeklyGamePlayer) : null;
    }

    get uuid() {
        return this._uuid;
    }

    get name() {
        return this._name;
    }

    get weeklyGamePlayer() {
        return this._weeklyGamePlayer;
    }

    get salaryUsed() {
        if (this._weeklyGamePlayer) {
            return this._weeklyGamePlayer.salary;
        } else  {
            return 0;
        }
    }
}