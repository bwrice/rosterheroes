import Model from './Model'
import PlayerSpiritApiModel from "./PlayerSpiritApiModel";

export default class Week extends Model {

    primaryKey() {
        return 'uuid';
    }

    playerSpirits() {
        return this.hasMany(PlayerSpiritApiModel);
    }
}
