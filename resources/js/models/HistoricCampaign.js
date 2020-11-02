
export default class HistoricCampaign {

    constructor({uuid, continentID, description = ''}) {
        this.uuid = uuid;
        this.continentID = continentID;
        this.description = description;
    }
}
