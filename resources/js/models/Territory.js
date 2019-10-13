
export default class Territory {

    constructor({id, name = '', slug = '', realmColor = '', realmViewBox = {}}) {
        this.id = id;
        this.name = name;
        this.slug = slug;
        this.realmColor = realmColor;
        this.realmViewBox = realmViewBox;
    }
}
