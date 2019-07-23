import Model from './Model'

export default class Game extends Model {

    primaryKey() {
        return 'uuid';
    }

    get description() {
        return this.startsAt;
    }
}