import MaterialType from "./MaterialType";

export default class Material {

    constructor({name = '', materialType}) {
        this.name = name;
        this.materialType = materialType ? new MaterialType(materialType) : new MaterialType({});
    }
}
