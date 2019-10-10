
export default class Position {

    constructor({id, name = '', abbreviation = '', sportID = 0}) {
        this.id = id;
        this.name = name;
        this.abbreviation = abbreviation;
        this.sportID = sportID;
    }
}
