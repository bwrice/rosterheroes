import Model from './Model'
import Player from "./Player";
import Game from "./Game";

export default class PlayerSpirit extends Model {

    primaryKey() {
        return 'uuid';
    }

    resource() {
        return 'player-spirits';
    }

    get playerModel() {
        return new Player(this.player);
    }

    get gameModel() {
        return new Game(this.game);
    }

    get playerName() {
        return this.playerModel.name;
    }

    get gameDescription() {
        return this.gameModel.description;
    }
}