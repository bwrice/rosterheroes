import Item from "./Item";
import Slot from "./Slot";
import HasSlots from "./HasSlots";

export default class SlotTransaction {
    constructor({type, item, slots = [], hasSlots}) {
        this.type = type;
        this.item = item ? new Item(item) : new Item({});
        this.slots = slots.map(function (slot) {
            return new Slot(slot);
        });
        this.hasSlots = hasSlots ? new HasSlots(hasSlots) : new HasSlots({});
    }

    syncSlots({state, commit, dispatch}) {
        switch (this.hasSlots.type) {
            case 'hero':
                this.updateHeroes(state.barracksHeroes, commit);
                return;
            case 'squad':
                this.updateMobileStorage(state.mobileStorage, commit);
                return;
            default:
                return;
        }
    }

    updateHeroes(heroes, commit) {
        let heroUuid = this.hasSlots.uniqueIdentifer;
        let newHeroes = _.cloneDeep(heroes);
        let matchingHero = newHeroes.find(function (hero) {
            return heroUuid === hero.uuid;
        });
        // let itemToFill = (this.type === 'fill') ? this.item : null;
        this.slots.forEach(function (newSlot) {
            let slotIndex = matchingHero.slots.findIndex(function (oldSlot) {
                return oldSlot.uuid === newSlot.uuid;
            });
            matchingHero.slots.splice(slotIndex, 1, new Slot(newSlot));
        });
        commit('SET_BARRACKS_HEROES', newHeroes);
    }

    updateMobileStorage(mobileStorage, commit) {
        let newMobileStorage = _.cloneDeep(mobileStorage);
        this.slots.forEach(function (newSlot) {
            let slotIndex = newMobileStorage.slots.findIndex(function (oldSlot) {
                return oldSlot.uuid === newSlot.uuid;
            });
            newMobileStorage.slots.splice(slotIndex, 1, new Slot(newSlot));
        });
        commit('SET_MOBILE_STORAGE', newMobileStorage);
    }
}
