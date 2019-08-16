import Model from './Model'

export default class PlayerSpirit extends Model {

    primaryKey() {
        return 'uuid';
    }

    resource() {
        return 'player-spirits';
    }
}
