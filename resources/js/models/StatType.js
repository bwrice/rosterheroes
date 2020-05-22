export default class StatType {

    constructor({id, sportID, name = '', pointsPer = 0}) {
        this.id = id;
        this.sportID = sportID;
        this.name = name;
        this.pointsPer = pointsPer;
    }
}
