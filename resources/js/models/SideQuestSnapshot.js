
export default class SideQuestSnapshot {

    constructor({uuid, weekID, name = '', minionSnapshots = []}) {
        this.uuid = uuid;
        this.weekID = weekID;
        this.name = name;
    }
}
