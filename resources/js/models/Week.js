export default class Week {

    constructor({uuid, adventuringLocksAt = null, secondsUntilAdventuringLocks = 0}) {
        this.uuid = uuid;
        this.adventuringLocksAt = adventuringLocksAt ? moment(adventuringLocksAt) : null;
        this.secondsUntilAdventuringLocks = secondsUntilAdventuringLocks;

        setInterval(() => this.secondsUntilAdventuringLocks -= 1, 1000);
    }

}
