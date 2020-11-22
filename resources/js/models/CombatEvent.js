export default class CombatEvent {

    constructor({uuid, moment = 0, eventType = '', data = {}}) {
        this.uuid = uuid;
        this.moment = moment;
        this.eventType = eventType;
        this.data = data;
    }

}
