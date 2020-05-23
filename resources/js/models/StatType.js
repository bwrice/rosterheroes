export default class StatType {

    constructor({id, sportID, name = '', simpleName = '', pointsPer = 0}) {
        this.id = id;
        this.sportID = sportID;
        this.name = name;
        this.simpleName = simpleName;
        this.pointsPer = pointsPer;
    }
}
