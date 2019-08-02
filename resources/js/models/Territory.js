import Model from './Model'

export default class Territory extends Model {

    resource() {
        return 'territories';
    }

    primaryKey() {
        return 'name';
    }
}