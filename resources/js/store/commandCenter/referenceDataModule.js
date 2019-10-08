import * as referenceApi from '../../api/referenceApi';
import HeroClass from "../../models/HeroClass";
import HeroRace from "../../models/HeroRace";

export default {

    state: {
        heroClasses: [],
        heroRaces: []
    },

    getters: {
        _heroClasses(state) {
            return state.heroClasses;
        },
        _heroRaces(state) {
            return state.heroRaces;
        },
        _heroClassByID: (state) => (heroClassID) => {
            let heroClass = state.heroClasses.find(function (heroClass) {
                return heroClass.id === heroClassID;
            });
            return heroClass ? heroClass : new HeroClass({});
        },
        _heroRaceByID: (state) => (heroRaceID) => {
            let heroRace = state.heroRaces.find(function (heroRace) {
                return heroRace.id === heroRaceID;
            });
            return heroRace ? heroRace : new HeroRace({});
        }
    },
    mutations: {
        SET_HERO_CLASSES(state, payload) {
            state.heroClasses = payload;
        },
        SET_HERO_RACES(state, payload) {
            state.heroRaces = payload;
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
        async updateHeroRaces({commit}) {
            try {
                let heroRacesResponse = await referenceApi.getHeroRaces();
                let heroRaces = heroRacesResponse.data.map(function (heroRace) {
                    return new HeroRace(heroRace);
                });
                commit('SET_HERO_RACES', heroRaces);
            } catch (e) {
                console.warn("Failed to update hero races");
            }
        },
    }
};
