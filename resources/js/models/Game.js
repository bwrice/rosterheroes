import Model from './Model'
import moment from 'moment';

export default class Game extends Model {

    primaryKey() {
        return 'uuid';
    }

    get description() {
        return this.startsAtMoment.format('ddd, MMM Do h:mm:ss a')
    }

    get startsAtMoment() {
        return moment(this.startsAt);
    }
}