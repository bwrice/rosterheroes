import Slot from "./Slot";
import Measurable from "./Measurable";
import PlayerSpirit from "./PlayerSpirit";

export default class Hero {

    constructor({name = '', uuid, slug = '', measurables = [], slots = [], heroClassID = 0, heroRaceID = 0, combatPositionID = 0, playerSpirit}) {
        this.name = name;
        this.uuid = uuid;
        this.slug = slug;
        this.slots = slots.map(function (slot) {
            return new Slot(slot);
        });
        this.measurables = measurables.map(function (measurable) {
            return new Measurable(measurable);
        });
        this.heroClassID = heroClassID;
        this.heroRaceID = heroRaceID;
        this.combatPositionID = combatPositionID;
        this.playerSpirit = playerSpirit ? new PlayerSpirit(playerSpirit) : null;
    }

    getSlot(slotUuid) {
        let matchingSlot = this.slots.find(slot => slot.uuid === slotUuid);
        return matchingSlot ? matchingSlot : new Slot({});
    }

    getMeasurableByType(measurableType) {
        return this.measurables.find(measurable => measurable.measurableType.name === measurableType);
    }
}
