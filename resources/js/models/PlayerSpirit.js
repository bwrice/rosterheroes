import Player from "./Player";
import Game from "./Game";

export default class PlayerSpirit {

    constructor({uuid, essenceCost, energy, player, game}) {
        this.uuid = uuid;
        this.essenceCost = essenceCost;
        this.energy = energy;
        this.player = player ? new Player(player) : new Player({});
        this.game = game ? new Game(game) : new Game({});
    }
}
