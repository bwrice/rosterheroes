import MinionSnapshot from "./MinionSnapshot";

export default class SideQuestSnapshot {

    constructor({uuid, weekID, name = '', minionSnapshots = []}) {
        this.uuid = uuid;
        this.weekID = weekID;
        this.name = name;
        this.minionSnapshots = minionSnapshots.map(snapshot => new MinionSnapshot(snapshot));
    }
}
