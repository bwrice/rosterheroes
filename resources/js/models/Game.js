import Team from "./Team";

export default class Game {

    constructor({id, startsAt, description = '', homeTeamID = 0, awayTeamID = 0}) {
        this.id = id;
        this.startsAt = startsAt;
        this.description = description;
        this.homeTeamID = homeTeamID;
        this.awayTeamID = awayTeamID;
    }
}
