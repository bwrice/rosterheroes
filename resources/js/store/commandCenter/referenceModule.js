import * as referenceApi from '../../api/referenceApi';
import HeroClass from "../../models/HeroClass";
import HeroRace from "../../models/HeroRace";
import CombatPosition from "../../models/CombatPosition";
import Position from "../../models/Position";
import Team from "../../models/Team";
import Sport from "../../models/Sport";
import MeasurableType from "../../models/MeasurableType";
import League from "../../models/League";
import StatType from "../../models/StatType";
import DamageType from "../../models/DamageType";

export default {

    state: {
        heroClasses: [],
        heroRaces: [],
        positions: [],
        combatPositions: [],
        damageTypes: [],
        teams: [],
        sports: [],
        measurableTypes: [],
        leagues: [],
        statTypes: []
    },

    getters: {
        _heroClasses(state) {
            return state.heroClasses;
        },
        _heroRaces(state) {
            return state.heroRaces;
        },
        _measurableTypes(state) {
            return state.measurableTypes;
        },
        _qualityTypes(state) {
            return state.measurableTypes.filter(function (measurableType) {
                return measurableType.group === 'quality';
            })
        },
        _positions(state) {
            return state.positions;
        },
        _combatPositions(state) {
            return state.combatPositions;
        },
        _damageTypes(state) {
            return state.damageTypes;
        },
        _teams(state) {
            return state.teams;
        },
        _sports(state) {
            return state.sports;
        },
        _leagues(state) {
            return state.leagues;
        },
        _statTypes(state) {
            return state.statTypes;
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
        _measurableTypeByID: (state) => (measurableTypeID) => {
            let measurableType = state.measurableTypes.find(function (measurableType) {
                return measurableType.id === measurableTypeID;
            });
            return measurableType ? measurableType : new MeasurableType({});
        },
        _measurableTypeByName: (state) => (measurableTypeName) => {
            let measurableType = state.measurableTypes.find(function (measurableType) {
                return measurableType.name === measurableTypeName;
            });
            return measurableType ? measurableType : new MeasurableType({});
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
        },
        _damageTypeByID: (state) => (damageTypeID) => {
            let damageType = state.damageTypes.find(function (damageType) {
                return damageType.id === damageTypeID;
            });
            return damageType ? damageType : new DamageType({});
        },
        _teamByID: (state) => (teamID) => {
            let team = state.teams.find(team => team.id === teamID);
            return team ? team : new Team({});
        },
        _sportByID: (state) => (sportID) => {
            let sport = state.sports.find(sport => sport.id === sportID);
            return sport ? sport : new Sport({});
        },
        _statTypeByID: (state) => (statTypeID) => {
            let statType = state.statTypes.find(statType => statType.id === statTypeID);
            return statType ? statType : new StatType({});
        }
    },
    mutations: {
        SET_HERO_CLASSES(state, payload) {
            state.heroClasses = payload;
        },
        SET_HERO_RACES(state, payload) {
            state.heroRaces = payload;
        },
        SET_MEASURABLE_TYPES(state, payload) {
            state.measurableTypes = payload;
        },
        SET_POSITIONS(state, payload) {
            state.positions = payload;
        },
        SET_COMBAT_POSITIONS(state, payload) {
            state.combatPositions = payload;
        },
        SET_DAMAGE_TYPES(state, damageTypes) {
            state.damageTypes = damageTypes;
        },
        SET_TEAMS(state, payload) {
            state.teams = payload;
        },
        SET_SPORTS(state, payload) {
            state.sports = payload;
        },
        SET_LEAGUES(state, leagues) {
            state.leagues = leagues;
        },
        SET_STAT_TYPES(state, statTypes) {
            state.statTypes = statTypes;
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
        async updateMeasurableTypes({commit}) {
            try {
                let response = await referenceApi.getMeasurableTypes();
                let measurableTypes = response.data.map(function (measurableType) {
                    return new MeasurableType(measurableType);
                });
                commit('SET_MEASURABLE_TYPES', measurableTypes);
            } catch (e) {
                console.warn("Failed to update measurable types");
            }
        },
        async updatePositions({commit}) {
            try {
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
        async updateDamageTypes({commit}) {
            try {
                let damageTypesResponse = await referenceApi.getDamageTypes();
                let damageTypes = damageTypesResponse.data.map(function (damageType) {
                    return new DamageType(damageType)
                });
                commit('SET_DAMAGE_TYPES', damageTypes);
            } catch (e) {
                console.warn("Failed to update damage types");
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
        async updateSports({commit}) {
            try {
                let sportsResponse = await referenceApi.getSports();
                let sports = sportsResponse.data.map(function (sport) {
                    return new Sport(sport);
                });
                commit('SET_SPORTS', sports);
            } catch (e) {
                console.warn("Failed to update sports");
            }
        },
        async updateLeagues({commit}) {
            try {
                let leaguesResponse = await referenceApi.getLeagues();
                let leagues = leaguesResponse.data.map(function (league) {
                    return new League(league);
                });
                commit('SET_LEAGUES', leagues);
            } catch (e) {
                console.warn("Failed to update leagues");
            }
        },
        async updateStatTypes({commit}) {
            try {
                let statTypesResponse = await referenceApi.getStatTypes();
                let statTypes = statTypesResponse.data.map(function (statType) {
                    return new StatType(statType);
                });
                commit('SET_STAT_TYPES', statTypes);
            } catch (e) {
                console.warn("Failed to update stat types");
            }
        },
    }
};
