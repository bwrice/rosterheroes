export default class Player {

    constructor({firstName = '', lastName = ''}) {
        this.firstName = firstName;
        this.lastName = lastName;
    }

    get fullName() {
        return this.firstName + ' ' + this.lastName;
    }
}
