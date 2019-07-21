import Model from './Model'

export default class Hero extends Model {

    constructor(week) {
        super();
        this._uuid = week.uuid;
    }

    get uuid() {
        return this._uuid;
    }
}