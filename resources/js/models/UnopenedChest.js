import ChestSource from "./ChestSource";

export default class UnopenedChest {

    constructor({uuid, description, source = null}) {
        this.uuid = uuid;
        this.description = description;
        this.source = source ? new ChestSource(source) : null;
    }
}
