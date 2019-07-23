import Model from './Model'
import PlayerSpirit from "./PlayerSpirit";

export default class Hero extends Model {

    get essenceUsed() {
        if (this.playerSpirit) {
            return this.playerSpirit.essence_cost;
        } else  {
            return 0;
        }
    }
}