import PlayerGameLog from "./PlayerGameLog";

export default class PlayerSpirit {

    constructor({uuid, essenceCost = 0, energy, playerGameLog}) {
        this.uuid = uuid;
        this.essenceCost = essenceCost;
        this.energy = energy;
        this.playerGameLog = playerGameLog ? new PlayerGameLog(playerGameLog) : new PlayerGameLog({});
    }

    get fullName() {
        return this.playerGameLog.player.fullName;
    }
}
