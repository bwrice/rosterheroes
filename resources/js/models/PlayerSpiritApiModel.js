import Model from './Model'

export default class PlayerSpiritApiModel extends Model {

    primaryKey() {
        return 'uuid';
    }

    resource() {
        return 'player-spirits';
    }
}
