import Model from './Model'

export default class PlayerSpirit extends Model {

    primaryKey() {
        return 'uuid';
    }

    resource() {
        return 'player-spirits';
    }

    get playerName() {
        return this.player.first_name + ' ' + this.player.last_name;
    }
}