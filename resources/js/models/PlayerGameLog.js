import Player from "./Player";

export default class PlayerGameLog {

    constructor({gameID, teamID, player}) {
        this.gameID = gameID;
        this.teamID = teamID;
        this.player = player ? new Player(player) : new Player({});
    }

    get fullName() {
        return this.player.fullName;
    }
}
