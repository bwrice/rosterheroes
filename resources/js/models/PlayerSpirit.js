import Player from "./Player";
import Game from "./Game";

export default class PlayerSpirit {

    constructor({uuid, essenceCost = 0, energy, gameID, player}) {
        this.uuid = uuid;
        this.essenceCost = essenceCost;
        this.energy = energy;
        this.gameID = gameID;
        this.player = player ? new Player(player) : new Player({});
    }

    get fullName() {
        return this.player.fullName;
    }
}
