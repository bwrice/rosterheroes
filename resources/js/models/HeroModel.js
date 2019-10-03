import Model from './Model'
import PlayerSpiritApiModel from "./PlayerSpiritApiModel";

export default class HeroModel extends Model {

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
            return new PlayerSpiritApiModel(this.playerSpirit);
        }
        return null;
    }
}
