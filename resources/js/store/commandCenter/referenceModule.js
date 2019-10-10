import * as referenceApi from '../../api/referenceApi';
import HeroClass from "../../models/HeroClass";
import HeroRace from "../../models/HeroRace";
import CombatPosition from "../../models/CombatPosition";
import Position from "../../models/Position";
import Team from "../../models/Team";

export default {

    state: {
        heroClasses: [],
        heroRaces: [],
        positions: [],
        combatPositions: [],
        teams: [],
    },

    getters: {
        _heroClasses(state) {
            return state.heroClasses;
        },
        _heroRaces(state) {
            return state.heroRaces;
        },
        _positions(state) {
            return state.positions;
        },
        _combatPositions(state) {
            return state.combatPositions;
        },
        _teams(state) {
            return state.teams;
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
        },
        _positionByID: (state) => (positionID) => {
            let position = state.positions.find(function (position) {
                return position.id === positionID;
            });
            return position ? position : new Position({});
        },
        _positionsFilteredByIDs: (state) => (positionIDs) => {
            return state.positions.filter(function (position) {
                return positionIDs.includes(position.id);
            })
        },
        _combatPositionByID: (state) => (combatPositionID) => {
            let combatPosition = state.combatPositions.find(function (combatPosition) {
                return combatPosition.id === combatPositionID;
            });
            return combatPosition ? combatPosition : new CombatPosition({});
        }
    },
    mutations: {
        SET_HERO_CLASSES(state, payload) {
            state.heroClasses = payload;
        },
        SET_HERO_RACES(state, payload) {
            state.heroRaces = payload;
        },
        SET_POSITIONS(state, payload) {
            state.positions = payload;
        },
        SET_COMBAT_POSITIONS(state, payload) {
            state.combatPositions = payload;
        },
        SET_TEAMS(state, payload) {
            state.teams = payload;
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
        async updatePositions({commit}) {
            try {
                console.log("Update Positions");
                let positionsResponse = await referenceApi.getPositions();
                let positions = positionsResponse.data.map(function (position) {
                    return new Position(position);
                });
                commit('SET_POSITIONS', positions);
            } catch (e) {
                console.warn("Failed to update positions");
            }
        },
        async updateCombatPositions({commit}) {
            try {
                let combatPositionsResponse = await referenceApi.getCombatPositions();
                let combatPositions = combatPositionsResponse.data.map(function (combatPosition) {
                    return new CombatPosition(combatPosition)
                });
                commit('SET_COMBAT_POSITIONS', combatPositions);
            } catch (e) {
                console.warn("Failed to update combat positions");
            }
        },
        async updateTeams({commit}) {
            try {
                let teamsResponse = await referenceApi.getTeams();
                let teams = teamsResponse.data.map(function (team) {
                    return new Team(team);
                });
                commit('SET_TEAMS', teams);
            } catch (e) {
                console.warn("Failed to update teams");
            }
        },
    }
};
