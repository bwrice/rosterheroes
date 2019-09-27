import Slot from "./Slot";
import MobileStorageRank from "./MobileStorageRank";

export default class MobileStorage {

    constructor({mobileStorageRank, slots = []}) {
        this.mobileStorageRank = mobileStorageRank ? new MobileStorageRank(mobileStorageRank) : new MobileStorageRank({});
        this.slots = slots.map(function (slot) {
            return new Slot(slot);
        })
    }

    get filledSlots() {
        let slotsWithItems = this.slots.filter(function (slot) {
            return slot.item;
        });
        return _.uniqBy(slotsWithItems, function (slot) {
            return slot.item.uuid;
        })
    }
}
