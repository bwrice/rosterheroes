import Model from './Model'
import moment from 'moment';
import Team from "./Team";

export default class Game extends Model {

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
        return new Team(this.homeTeam);
    }

    get awayTeamModel() {
        return new Team(this.awayTeam);
    }
}