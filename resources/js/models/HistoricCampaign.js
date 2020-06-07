export default class HistoricCampaign {

    constructor({uuid, description = '', stops = []}) {
        this.uuid = uuid;
        this.description = description;
        this.stops = stops;
    }
}
