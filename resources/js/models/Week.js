import Model from './Model'
import PlayerSpirit from "./PlayerSpirit";

export default class Week extends Model {

    primaryKey() {
        return 'uuid';
    }

    playerSpirits() {
        return this.hasMany(PlayerSpirit);
    }
}