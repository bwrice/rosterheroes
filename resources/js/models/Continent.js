import Model from './Model'

export default class Continent extends Model {

    primaryKey() {
        return 'name';
    }
}