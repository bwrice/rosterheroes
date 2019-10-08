
export default class HeroClass {

    constructor({id, name = '', svg = ''}) {
        this.id = id;
        this.name = name;
        this.svg = svg;
    }
}
