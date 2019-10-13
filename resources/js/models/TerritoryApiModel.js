import Model from './Model'

export default class TerritoryApiModel extends Model {

    resource() {
        return 'territories';
    }

    primaryKey() {
        return 'name';
    }
}
