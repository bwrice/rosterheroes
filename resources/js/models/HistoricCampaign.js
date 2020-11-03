
export default class HistoricCampaign {

    constructor({uuid, weekID, continentID, description = ''}) {
        this.uuid = uuid;
        this.weekID = weekID;
        this.continentID = continentID;
        this.description = description;
    }
}
