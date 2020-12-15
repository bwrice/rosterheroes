export default class CombatEventMessage {

    constructor({eventUuid, moment = 0, message = '', allySide = true}) {
        this.eventUuid = eventUuid;
        this.moment = moment;
        this.message = message;
        this.allySide = allySide;
    }

}
