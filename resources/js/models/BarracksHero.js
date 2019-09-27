import Slot from "./Slot";
import Measurable from "./Measurable";

export default class BarracksHero {

    constructor({name = '', uuid, slug, measurables = [], slots = []}) {
        this.name = name;
        this.uuid = uuid;
        this.slug = slug;
        this.slots = slots.map(function (slot) {
            return new Slot(slot);
        });
        this.measurables = measurables.map(function (measurable) {
            return new Measurable(measurable);
        })
    }

    getSlot(slotUuid) {
        let matchingSlot = this.slots.find(slot => slot.uuid === slotUuid);
        return matchingSlot ? matchingSlot : new Slot({});
    }
}
