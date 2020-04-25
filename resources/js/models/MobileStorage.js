import Slot from "./Slot";
import MobileStorageRank from "./MobileStorageRank";
import Item from "./Item";

export default class MobileStorage {

    constructor({maxCapacity, capacityUsed, mobileStorageRank, items = []}) {
        this.maxCapacity = maxCapacity ? maxCapacity : 0;
        this.capacityUsed = capacityUsed ? capacityUsed : 0;
        this.mobileStorageRank = mobileStorageRank ? new MobileStorageRank(mobileStorageRank) : new MobileStorageRank({});
        this.items = items.map(function (item) {
            return new Item(item);
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

    getItemMovedToText(item) {
        console.log("Mobile Storage Rank");
        console.log(this.mobileStorageRank);
        return item.name + ' moved to ' + this.mobileStorageRank.name;
    }
}
