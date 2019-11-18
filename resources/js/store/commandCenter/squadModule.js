import * as squadApi from '../../api/squadApi';
import * as heroApi from '../../api/heroApi';
import * as helpers from '../../helpers/vuexHelpers';
import Hero from "../../models/Hero";
import MobileStorage from "../../models/MobileStorage";
import SlotTransaction from "../../models/SlotTransaction";
import Squad from "../../models/Squad";
import Spell from "../../models/Spell";
import Campaign from "../../models/Campaign";

export default {

    state: {
        squad: new Squad({}),
        heroes: [],
        mobileStorage: new MobileStorage({}),
        barracksLoading: true,
        currentCampaign: null,
        spells: []
    },

    getters: {
        _squad(state) {
            return state.squad;
        },
        _spellLibrary(state) {
            return state.spells;
        },
        _heroes(state) {
            return state.heroes;
        },
        _currentCampaign(state) {
            return state.currentCampaign;
        },
        _mobileStorage(state) {
            return state.mobileStorage;
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
                return hero.getMeasurableByTypeID(measurableTypeID).currentAmount;
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
        },
        _enlistedForQuest: (state) => (questID) => {
            if (! state.currentCampaign) {
                return false;
            }
            let match = state.currentCampaign.campaignStops.find(campaignStop => campaignStop.questID === questID);
            return match !== undefined;
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        },
        SET_SPELL_LIBRARY(state, payload) {
            state.spells = payload;
        },
        SET_HEROES(state, payload) {
            state.heroes = payload;
        },
        SET_MOBILE_STORAGE(state, payload) {
            state.mobileStorage = payload;
        },
        SET_CURRENT_CAMPAIGN(state, payload) {
            state.currentCampaign = payload;
        },
        SET_BARRACKS_LOADING(state, payload) {
            state.barracksLoading = payload;
        }
    },

    actions: {

        async updateSquad({commit}, route) {
            let squadResponse = await squadApi.getSquad(route.params.squadSlug);
            commit('SET_SQUAD', new Squad(squadResponse.data));
        },


        async updateSpellLibrary({commit}, route) {
            let response = await squadApi.getSpellLibrary(route.params.squadSlug);
            let spells = response .data.map(spellData => new Spell(spellData));
            commit('SET_SPELL_LIBRARY', spells);
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
                commit('SET_MOBILE_STORAGE', new MobileStorage(mobileStorageResponse.data));
            } catch (e) {
                console.warn("Failed to update mobile storage");
            }
        },

        async updateCurrentCampaign({commit}, route) {
            try {
                let squadSlug = route.params.squadSlug;
                let currentCampaignResponse = await squadApi.getCurrentCampaign(squadSlug);
                let currentCampaign = currentCampaignResponse.data ? new Campaign(currentCampaignResponse.data) : null;
                commit('SET_CURRENT_CAMPAIGN', currentCampaign);
            } catch (e) {
                console.warn("Failed to update current campaign");
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

        async unequipItem({state, commit, dispatch}, {heroSlug, item}) {

            try {
                let response = await heroApi.unequipItem(heroSlug, item.uuid);
                helpers.syncHasItemsResponse(state, commit, response);
                let mobileStorageResponse = response.data.find(function (hasItemsResponse) {
                    return hasItemsResponse.type === 'squad';
                });
                if (mobileStorageResponse) {
                    let mobileStorage = new MobileStorage(mobileStorageResponse.hasItems);
                    dispatch('snackBarSuccess', {
                        text: mobileStorage.getItemMovedToText(item),
                        timeout: 3000
                    });
                }
            } catch (e) {
                helpers.handleResponseErrors(e, 'equip', dispatch);
            }
        },

        async equipHeroFromMobileStorage({state, commit, dispatch}, {heroSlug, item}) {

            try {
                let response = await heroApi.equipFromWagon(heroSlug, item.uuid);
                helpers.syncHasItemsResponse(state, commit, response);
                dispatch('snackBarSuccess', {
                    text: item.name + ' equipped',
                    timeout: 3000
                });
            } catch (e) {
                helpers.handleResponseErrors(e, 'equip', dispatch);
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
        },

        async castSpellOnHero({state, commit, dispatch}, {hero, spell}) {
            try {

                let heroResponse = await heroApi.castSpell(hero.slug, spell.id);
                let updatedHero = new Hero(heroResponse.data);
                helpers.syncUpdatedHero(state, commit, updatedHero);
                let text = spell.name + " cast on " + updatedHero.name;
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'spellCaster', dispatch);
            }
        },

        async removeSpellOnHero({state, commit, dispatch}, {hero, spell}) {
            try {

                let heroResponse = await heroApi.removeSpell(hero.slug, spell.id);
                let updatedHero = new Hero(heroResponse.data);
                helpers.syncUpdatedHero(state, commit, updatedHero);
                let text = spell.name + " removed from " + updatedHero.name;
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'spellCaster', dispatch);
            }
        }
    },
};
