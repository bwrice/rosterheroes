import Slot from "./Slot";
import MobileStorageRank from "./MobileStorageRank";

export default class MobileStorage {

    constructor({mobileStorageRank, slots = []}) {
        this.mobileStorageRank = mobileStorageRank ? new MobileStorageRank(mobileStorageRank) : new MobileStorageRank({});
        this.slots = slots.map(function (slot) {
            return new Slot(slot);
        })
    }
}
