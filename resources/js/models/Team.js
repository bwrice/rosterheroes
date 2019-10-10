export default class Team {

    constructor({id, name = '', location = '', abbreviation = ''}) {
        this.id = id;
        this.name = name;
        this.location = location;
        this.abbreviation = abbreviation;
    }
}
