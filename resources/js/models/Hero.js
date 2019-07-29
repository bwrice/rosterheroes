import Model from './Model'
import PlayerSpirit from "./PlayerSpirit";

export default class Hero extends Model {

    resource() {
        return 'heroes';
    }

    get essenceUsed() {
        if (this.playerSpirit) {
            return this.playerSpirit.essence_cost;
        } else  {
            return 0;
        }
    }

    get playerSpiritObject() {
        if (this.playerSpirit) {
            return new PlayerSpirit(this.playerSpirit);
        }
        return null;
    }
}