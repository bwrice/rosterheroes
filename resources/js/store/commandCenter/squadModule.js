import * as squadApi from '../../api/squadApi';
import * as heroApi from '../../api/heroApi';
import * as helpers from '../../helpers/vuexHelpers';
import Hero from "../../models/Hero";
import MobileStorage from "../../models/MobileStorage";
import SlotTransaction from "../../models/SlotTransaction";
import Squad from "../../models/Squad";
import CurrentLocation from "../../models/CurrentLocation";

export default {

    state: {
        squad: new Squad({}),
        heroes: [],
        mobileStorage: new MobileStorage({}),
        barracksLoading: true,
        currentLocation: new CurrentLocation({})
    },

    getters: {
        _squad(state) {
            return state.squad;
        },
        _heroes(state) {
            return state.heroes;
        },
        _mobileStorage(state) {
            return state.mobileStorage;
        },
        _currentLocation(state) {
            return state.currentLocation;
        },
        _barracksLoading(state) {
            return state.heroes.length <= 0;
        },
        _focusedHero: (state) => (route) => {
            let hero = state.heroes.find(hero => hero.slug === route.params.heroSlug);
            return hero ? hero : new Hero({});
        },
        _squadHighMeasurable: (state) => (measurableTypeID) => {
            let measurableAmounts = state.heroes.map(function (hero) {
                return hero.getMeasurableByTypeID(measurableTypeID).buffedAmount;
            });
            return measurableAmounts.reduce(function(amountA, amountB) {
                return Math.max(amountA, amountB);
            }, 0);
        },
        _availableSpiritEssence(state) {
            let essenceUsed = state.heroes.reduce(function (essence, hero) {
                if (hero.playerSpirit) {
                    return essence + hero.playerSpirit.essenceCost;
                }
                return essence;
            }, 0);
            return state.squad.spiritEssence - essenceUsed;
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        },
        SET_HEROES(state, payload) {
            state.heroes = payload;
        },
        SET_MOBILE_STORAGE(state, payload) {
            state.mobileStorage = payload;
        },
        SET_BARRACKS_LOADING(state, payload) {
            state.barracksLoading = payload;
        },
        SET_CURRENT_LOCATION(state, payload) {
            state.currentLocation = payload;
        }
    },

    actions: {

        async updateSquad({commit}, route) {
            let squadResponse = await squadApi.getSquad(route.params.squadSlug);
            commit('SET_SQUAD', new Squad(squadResponse.data));
        },

        async updateCurrentLocation({commit}, route) {
            try {
                let locationResponse = await squadApi.getCurrentLocation(route.params.squadSlug);
                let currentLocation = new CurrentLocation(locationResponse.data);
                commit('SET_CURRENT_LOCATION', currentLocation)
            } catch (e) {
                console.warn("Failed to update current location");
            }
        },

        async updateHeroes({commit}, route) {
            try {
                let squadSlug = route.params.squadSlug;
                let heroesResponse = await squadApi.getHeroes(squadSlug);
                let heroes = heroesResponse.data.map(function (hero) {
                    return new Hero(hero);
                });
                commit('SET_HEROES', heroes);
            } catch (e) {
                console.warn("Failed to update heroes");
            }
        },

        async updateMobileStorage({commit}, route) {
            try {
                let squadSlug = route.params.squadSlug;
                let mobileStorageResponse = await squadApi.getMobileStorage(squadSlug);
                commit('SET_MOBILE_STORAGE', new MobileStorage(mobileStorageResponse));
            } catch (e) {
                console.warn("Failed to update mobile storage");
            }
        },

        async raiseHeroMeasurable({state, commit, dispatch}, {heroSlug, measurableTypeName, raiseAmount}) {

            try {
                let response = await heroApi.raiseMeasurable(heroSlug, measurableTypeName, raiseAmount);
                let updatedHero = new Hero(response.data);
                helpers.syncUpdatedHero(state, commit, updatedHero);

                let text = measurableTypeName.toUpperCase() + ' raised';
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })
            } catch(e) {
                helpers.handleResponseErrors(e, 'raiseMeasurable', dispatch);
            }
        },

        async emptyHeroSlot({state, commit, dispatch}, {heroSlug, slotUuid}) {

            try {
                let response = await heroApi.emptySlot(heroSlug, slotUuid);
                let updatedHero = new Hero(response.data.hero);
                helpers.syncUpdatedHero(state, commit, updatedHero);
                if (response.data.mobileStorage) {
                    let updateMobileStorage = new MobileStorage(response.data.mobileStorage);
                    commit('SET_MOBILE_STORAGE', updateMobileStorage);
                }
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {});
            }
        },

        async equipHeroSlotFromWagon({state, commit, dispatch}, {heroSlug, slotUuid, itemUuid}) {

            try {
                let response = await heroApi.equipFromWagon(heroSlug, slotUuid, itemUuid);
                let updatedHero = new Hero(response.data.hero);
                helpers.syncUpdatedHero(state, commit, updatedHero);
                if (response.data.mobileStorage) {
                    let updateMobileStorage = new MobileStorage(response.data.mobileStorage);
                    commit('SET_MOBILE_STORAGE', updateMobileStorage);
                }
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {})
            }
        },

        async addSpiritToHero({state, commit, dispatch}, payload) {

            try {

                let heroResponse = await heroApi.addSpirit(payload.heroSlug, payload.spiritUuid);
                let updatedHero = new Hero(heroResponse.data);
                helpers.syncUpdatedHero(state, commit, updatedHero);

                dispatch('snackBarSuccess', {
                    text: updatedHero.playerSpirit.player.fullName + ' now embodies ' + updatedHero.name,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'roster', dispatch);
            }
        },

        async removeSpiritFromHero({state, commit, dispatch}, payload) {
            try {

                let heroResponse = await heroApi.removeSpirit(payload.heroSlug, payload.spiritUuid);
                let updatedHero = new Hero(heroResponse.data);
                helpers.syncUpdatedHero(state, commit, updatedHero);

                dispatch('snackBarSuccess', {
                    text: 'Player spirit removed from ' + updatedHero.name,
                    timeout: 2000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'roster', dispatch);
            }
        },

        async changeHeroCombatPosition({state, commit, dispatch, getters}, {heroSlug, combatPositionID}) {

            try {

                let heroResponse = await heroApi.changeCombatPosition(heroSlug, combatPositionID);
                let updatedHero = new Hero(heroResponse.data);
                helpers.syncUpdatedHero(state, commit, updatedHero);
                let combatPosition = getters._combatPositionByID(updatedHero.combatPositionID);
                let text = updatedHero.name + ' fights from the ' + combatPosition.name + '!';
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'combatPosition', dispatch);
            }
        }
    },
};
