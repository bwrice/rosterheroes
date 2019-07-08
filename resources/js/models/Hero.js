import Model from './Model'
import PlayerSpirit from "./PlayerSpirit";

export default class Hero extends Model {

    constructor(hero) {
        super();
        this._uuid = hero.uuid;
        this._name = hero.name;
        this._playerSpirit = hero.playerSpirit ? new PlayerSpirit(hero.playerSpirit) : null;
    }

    get uuid() {
        return this._uuid;
    }

    get name() {
        return this._name;
    }

    get playerSpirit() {
        return this._playerSpirit;
    }

    get essenceUsed() {
        if (this._playerSpirit) {
            return this._playerSpirit.essence_cost;
        } else  {
            return 0;
        }
    }
}