export default class Player {

    constructor({firstName = '', lastName = '', positionIDs = []}) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.positionIDs = positionIDs;
    }

    get fullName() {
        return this.firstName + ' ' + this.lastName;
    }
}
