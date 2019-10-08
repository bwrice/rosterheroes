import Slot from "./Slot";
import Measurable from "./Measurable";
import CombatPosition from "./CombatPosition";
import PlayerSpirit from "./PlayerSpirit";

export default class BarracksHero {

    constructor({name = '', uuid, slug, measurables = [], slots = [], heroClassID, heroRaceID, combatPosition, playerSpirit}) {
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
        this.combatPosition = combatPosition ? new CombatPosition(combatPosition) : new CombatPosition({});
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
