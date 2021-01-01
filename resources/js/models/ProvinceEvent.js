
export default class ProvinceEvent {

    constructor({uuid, provinceUuid, squad, eventType, happenedAt, extra}) {
        this.uuid = uuid;
        this.provinceUuid = provinceUuid;
        this.squad = squad;
        this.eventType = eventType;
        this.happenedAt = happenedAt;
        this.extra = extra;
    }
}
