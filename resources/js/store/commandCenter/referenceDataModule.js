import * as referenceApi from '../../api/referenceApi';
import HeroClass from "../../models/HeroClass";

export default {

    state: {
        heroClasses: []
    },

    getters: {
        _heroClasses(state) {
            return state.heroClasses;
        },
        _heroClassByID: (state) => (heroClassID) => {
            let heroClass = state.heroClasses.find(function (heroClass) {
                return heroClass.id === heroClassID;
            });
            return heroClass ? heroClass : new HeroClass({});
        }
    },
    mutations: {
        SET_HERO_CLASSES(state, payload) {
            state.heroClasses = payload;
        },
    },

    actions: {
        async updateHeroClasses({commit}) {
            try {
                let heroClassesResponse = await referenceApi.getHeroClasses();
                let heroClasses = heroClassesResponse.data.map(function (heroClass) {
                    return new HeroClass(heroClass);
                });
                commit('SET_HERO_CLASSES', heroClasses);
            } catch (e) {
                console.warn("Failed to update hero classes");
            }
        },
    }
};
