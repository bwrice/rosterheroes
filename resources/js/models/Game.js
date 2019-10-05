import Team from "./Team";

export default class Game {

    constructor({startsAt, description = '', homeTeam, awayTeam}) {
        this.startsAt = startsAt;
        this.description = description;
        this.homeTeam = homeTeam ? new Team(homeTeam) : new Team({});
        this.awayTeam = awayTeam ? new Team(awayTeam) : new Team({});
    }

    get simpleDescription() {
        return this.awayTeam.abbreviation + " @ " + this.awayTeam.abbreviation;
    }
}
