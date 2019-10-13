import Model from './Model'

export default class ProvinceApiModel extends Model {

    primaryKey() {
        return 'slug';
    }
}
