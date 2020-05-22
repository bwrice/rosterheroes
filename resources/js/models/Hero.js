
import Measurable from "./Measurable";
import PlayerSpirit from "./PlayerSpirit";
import Spell from "./Spell";
import GearSlot from "./GearSlot";
import StatMeasurableBonus from "./StatMeasurableBonus";

export default class Hero {

    constructor({name = '', uuid, slug = '', measurables = [], gearSlots = [], heroClassID = 0, heroRaceID = 0, combatPositionID = 0, playerSpirit, spells = [], spellPower, manaUsed, statMeasurableBonuses = []}) {
        this.name = name;
        this.uuid = uuid;
        this.slug = slug;
        this.gearSlots = gearSlots.map(function (gearSlot) {
            return new GearSlot(gearSlot);
        });
        this.measurables = measurables.map(function (measurable) {
            return new Measurable(measurable);
        });
        this.heroClassID = heroClassID;
        this.heroRaceID = heroRaceID;
        this.combatPositionID = combatPositionID;
        this.playerSpirit = playerSpirit ? new PlayerSpirit(playerSpirit) : null;
        this.spells = spells.map(function (spell) {
            return new Spell(spell);
        });
        this.spellPower = spellPower;
        this.manaUsed = manaUsed;
        this.statMeasurableBonuses = statMeasurableBonuses.map(function (statMeasurableBonus) {
            return new StatMeasurableBonus(statMeasurableBonus);
        });
    }

    getGearSlotByType(slotType) {
        let matchingGearSlot = this.gearSlots.find(gearSlot => gearSlot.type === slotType);
        return matchingGearSlot ? matchingGearSlot : new GearSlot({});
    }

    getMeasurableByTypeID(measurableTypeID) {
        let measurable = this.measurables.find(measurable => measurable.measurableTypeID === measurableTypeID);
        return measurable ? measurable : new Measurable({});
    }
}
