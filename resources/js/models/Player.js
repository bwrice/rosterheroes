import Model from './Model'

export default class Player extends Model {

    primaryKey() {
        return 'uuid';
    }

    get name() {
        return this.first_name + ' ' + this.last_name;
    }
}