import Model from './Model'
import moment from 'moment';
import TeamApiModel from "./TeamApiModel";

export default class GameApiModel extends Model {

    primaryKey() {
        return 'uuid';
    }

    get description() {
        let description = this.awayTeam.abbreviation + '@' + this.homeTeam.abbreviation;
        return description + ' - ' + this.startsAtMoment.format('ddd, h:mm:ss a')
    }

    get startsAtMoment() {
        return moment(this.startsAt);
    }

    get homeTeamModel() {
        return new TeamApiModel(this.homeTeam);
    }

    get awayTeamModel() {
        return new TeamApiModel(this.awayTeam);
    }
}
