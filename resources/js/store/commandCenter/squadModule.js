import * as squadApi from '../../api/squadApi';
import * as heroApi from '../../api/heroApi';
import * as campaignStopApi from '../../api/campaignStopApi';
import * as chestApi from '../../api/chestApi';
import * as helpers from '../../helpers/vuexHelpers';
import Hero from "../../models/Hero";
import MobileStorage from "../../models/MobileStorage";
import Squad from "../../models/Squad";
import Spell from "../../models/Spell";
import Campaign from "../../models/Campaign";
import UnopenedChest from "../../models/UnopenedChest";
import OpenedChestResult from "../../models/OpenedChestResult";
import Item from "../../models/Item";
import HistoricCampaign from "../../models/HistoricCampaign";
import CampaignStopResult from "../../models/CampaignStopResult";
import GlobalStash from "../../models/GlobalStash";
import LocalStash from "../../models/LocalStash";

export default {

    state: {
        squad: new Squad({}),
        heroes: [],
        mobileStorage: new MobileStorage({}),
        localStash: new LocalStash({}),
        barracksLoading: true,
        currentCampaign: null,
        spells: [],
        unopenedChests: [],
        openedChestResults: [],
        loadingUnopenedChests: true,
        historicCampaigns: [],
        globalStashes: []
    },

    getters: {
        _squad(state) {
            return state.squad;
        },
        _spellLibrary(state) {
            return state.spells;
        },
        _unopenedChests(state) {
            return state.unopenedChests;
        },
        _loadingUnopenedChests(state) {
            return state.loadingUnopenedChests;
        },
        _historicCampaigns(state) {
            return state.historicCampaigns;
        },
        _lastOpenedChestResult(state) {
            if (state.openedChestResults.length > 0) {
                return _.last(state.openedChestResults);
            }
            return null;
        },
        _heroes(state) {
            return state.heroes;
        },
        _heroByUuid: (state) => (uuid) => {
            return state.heroes.find((hero) => hero.uuid === uuid);
        },
        _currentCampaign(state) {
            return state.currentCampaign;
        },
        _mobileStorage(state) {
            return state.mobileStorage;
        },
        _localStash(state) {
            return state.localStash;
        },
        _globalStashes(state) {
            return state.globalStashes;
        },
        _mobileStorageRankName(state) {
            return state.mobileStorage.mobileStorageRank.name;
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
        _totalSpiritEssence(state) {
            return state.squad.spiritEssence;
        },
        _matchingCampaignStop: (state) => (questUuid) => {
            if (! state.currentCampaign) {
                return false;
            }
            return state.currentCampaign.campaignStops.find(campaignStop => campaignStop.questUuid === questUuid);
        },
        _squadSideQuestUuids(state) {
            let campaign = state.currentCampaign;
            let uuids = [];
            if (! campaign) {
                return uuids;
            }
            campaign.campaignStops.forEach(function (campaignStop) {
                uuids = _.merge(uuids, campaignStop.sideQuestUuids);
            });
            return uuids;
        },
        _campaignStopResultByUuid: (state) => (stopUuid) => {
            let campaignStopResults = _.flatten(state.historicCampaigns.map((historicCampaign) => historicCampaign.campaignStopResults));
            let foundCampaignStopResult = campaignStopResults.find((campaignStopResult) => campaignStopResult.uuid === stopUuid);
            return foundCampaignStopResult ? new CampaignStopResult(foundCampaignStopResult) : new CampaignStopResult({});
        }
    },
    mutations: {
        SET_SQUAD(state, payload) {
            state.squad = payload;
        },
        INCREASE_SQUAD_GOLD(state, gold) {
            state.squad.gold += gold;
        },
        SET_SPELL_LIBRARY(state, payload) {
            state.spells = payload;
        },
        SET_LOCAL_STASH(state, payload) {
            state.localStash = payload;
        },
        ADD_ITEM_TO_LOCAL_STASH(state, payload) {
            state.localStash.items.push(payload);
        },
        REMOVE_ITEM_FROM_LOCAL_STASH(state, itemToRemove) {
            let localStash = _.cloneDeep(state.localStash);

            let index = localStash.items.findIndex(function (item) {
                return item.uuid === itemToRemove.uuid;
            });

            if (index !== -1) {
                localStash.items.splice(index, 1);
            }

            localStash.capacityUsed -= itemToRemove.weight;
            state.localStash = localStash;
        },
        SET_HISTORIC_CAMPAIGNS(state, historicCampaigns) {
            state.historicCampaigns = historicCampaigns;
        },
        SET_UNOPENED_CHESTS(state, payload) {
            state.unopenedChests = payload;
        },
        SET_LOADING_UNOPENED_CHESTS(state, payload) {
            state.loadingUnopenedChests = payload;
        },
        SET_HEROES(state, payload) {
            state.heroes = payload;
        },
        SET_MOBILE_STORAGE(state, payload) {
            state.mobileStorage = payload;
        },
        SET_GLOBAL_STASHES(state, globalStashes) {
            state.globalStashes = globalStashes;
        },
        ADD_ITEM_TO_MOBILE_STORAGE(state, item) {
            state.mobileStorage.capacityUsed += item.weight;
            state.mobileStorage.items.push(item);
        },
        REMOVE_ITEM_FROM_MOBILE_STORAGE(state, itemToRemove) {
            let mobileStorage = _.cloneDeep(state.mobileStorage);

            let index = mobileStorage.items.findIndex(function (item) {
                return item.uuid === itemToRemove.uuid;
            });

            if (index !== -1) {
                mobileStorage.items.splice(index, 1);
            }

            mobileStorage.capacityUsed -= itemToRemove.weight;
            state.mobileStorage = mobileStorage;
        },
        SET_CURRENT_CAMPAIGN(state, payload) {
            state.currentCampaign = payload;
        },
        SET_BARRACKS_LOADING(state, payload) {
            state.barracksLoading = payload;
        },
        ADD_TO_OPENED_CHEST_RESULTS(state, payload) {
            state.openedChestResults.push(payload);
        },
        REMOVE_CHEST_FROM_UNOPENED_CHESTS(state, payload) {
            state.unopenedChests = state.unopenedChests.filter(function (unopenedChest) {
                return unopenedChest.uuid !== payload;
            })
        },
        REPLACE_UPDATED_HERO(state, updatedHero) {
            state.heroes = helpers.replaceOrPushElement(state.heroes, updatedHero, 'uuid');
        },
        DECREASE_SQUAD_GOLD(state, amount) {
            let updatedSquad = _.cloneDeep(state.squad);
            updatedSquad.gold -= amount;
            state.squad = updatedSquad;
        }
    },

    actions: {

        async updateSquad({commit}, route) {
            let squadResponse = await squadApi.getSquad(route.params.squadSlug);
            commit('SET_SQUAD', new Squad(squadResponse.data));
        },


        async updateSpellLibrary({commit}, route) {
            let response = await squadApi.getSpellLibrary(route.params.squadSlug);
            let spells = response.data.map(spellData => new Spell(spellData));
            commit('SET_SPELL_LIBRARY', spells);
        },

        async updateLocalStash({commit}, route) {
            try {
                let response = await squadApi.getLocalStash(route.params.squadSlug);
                let localStash = new LocalStash(response.data);
                commit('SET_LOCAL_STASH', localStash)
            } catch (e) {
                console.warn("Failed to update local stash");
            }
        },

        async updateUnopenedChests({commit}, route) {
            try {
                let response = await squadApi.getUnopenedChests(route.params.squadSlug);
                let chests = response.data.map(chestData => new UnopenedChest(chestData));
                commit('SET_UNOPENED_CHESTS', chests);
            } catch (e) {
                console.warn("Failed to load unopened chests");
            }
            commit('SET_LOADING_UNOPENED_CHESTS', false);
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

        async updateHero({commit}, heroSlug) {
            let heroResponse = await heroApi.getHero(heroSlug);
            commit('REPLACE_UPDATED_HERO', new Hero(heroResponse.data));
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

        async updateGlobalStashes({commit}, route) {
            try {
                let squadSlug = route.params.squadSlug;
                let stashesResponse = await squadApi.getGlobalStashes(squadSlug);
                let globalStashes = stashesResponse.data.map(function (stashData) {
                    return new GlobalStash(stashData);
                });
                commit('SET_GLOBAL_STASHES', globalStashes);
            } catch (e) {
                console.warn("Failed to update global stashes");
            }
        },

        async updateHistoricCampaigns({commit}, route) {
            try {
                let squadSlug = route.params.squadSlug;
                let campaignHistoryResponse = await squadApi.getCampaignHistory(squadSlug);
                let historicCampaigns = campaignHistoryResponse.data.map(function (historicCampaign) {
                    return new HistoricCampaign(historicCampaign);
                });
                commit('SET_HISTORIC_CAMPAIGNS', historicCampaigns);
            } catch (e) {
                console.warn("Failed to update historic campaigns");
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
                let updatedItems = response.data.map(function (itemData) {
                    return new Item(itemData);
                });
                helpers.handleItemTransactions({state, commit, dispatch}, updatedItems);
                dispatch('snackBarSuccess', {
                    text: item.name + ' moved to ' + state.mobileStorage.mobileStorageRank.name,
                    timeout: 3000
                });
            } catch (e) {
                helpers.handleResponseErrors(e, 'equip', dispatch);
            }
        },

        async equipHeroFromMobileStorage({state, commit, dispatch}, {heroSlug, item}) {

            try {
                let response = await heroApi.equipFromWagon(heroSlug, item.uuid);
                let updatedItems = response.data.map(function (itemData) {
                    return new Item(itemData);
                });
                helpers.handleItemTransactions({state, commit, dispatch}, updatedItems);
                dispatch('snackBarSuccess', {
                    text: item.name + ' equipped',
                    timeout: 2000
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
                    text: updatedHero.playerSpirit.playerGameLog.player.fullName + ' now embodies ' + updatedHero.name,
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
        },

        async joinQuest({state, commit, dispatch}, {quest}) {
            try {

                let campaignResponse = await squadApi.joinQuest(state.squad.slug, quest.uuid);
                let updateCampaign = new Campaign(campaignResponse.data);
                commit('SET_CURRENT_CAMPAIGN', updateCampaign);
                let text = quest.name + ' added to campaign';
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'campaign', dispatch);
            }
        },

        async leaveQuest({state, commit, dispatch}, {questUuid, questName}) {
            try {

                let campaignResponse = await squadApi.leaveQuest(state.squad.slug, questUuid);
                let updatedCampaign = null;
                if (campaignResponse.data) {
                    updatedCampaign = new Campaign(campaignResponse.data);
                }
                commit('SET_CURRENT_CAMPAIGN', updatedCampaign);
                let text = questName + ' removed from campaign';
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'campaign', dispatch);
            }
        },

        async joinSideQuest({state, commit, dispatch}, {campaignStop, sideQuest}) {
            try {
                let campaignResponse = await campaignStopApi.joinSideQuest(campaignStop.uuid, sideQuest.uuid);
                let updateCampaign = new Campaign(campaignResponse.data);
                commit('SET_CURRENT_CAMPAIGN', updateCampaign);
                let text = sideQuest.name + ' added to campaign';
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'campaign', dispatch);
            }
        },

        async leaveSideQuest({state, commit, dispatch}, {campaignStop, sideQuest}) {
            try {
                let campaignResponse = await campaignStopApi.leaveSideQuest(campaignStop.uuid, sideQuest.uuid);
                let updateCampaign = new Campaign(campaignResponse.data);
                commit('SET_CURRENT_CAMPAIGN', updateCampaign);
                let text = sideQuest.name + ' removed from campaign';
                dispatch('snackBarSuccess', {
                    text: text,
                    timeout: 3000
                })

            } catch (e) {
                helpers.handleResponseErrors(e, 'campaign', dispatch);
            }
        },

        async openChest({state, commit, dispatch}, unopenedChest) {
            try {
                let response = await chestApi.open(unopenedChest.uuid);
                let openedChestResult = new OpenedChestResult(response.data);
                helpers.handleItemTransactions({state, commit, dispatch}, openedChestResult.items);
                commit('REMOVE_CHEST_FROM_UNOPENED_CHESTS', unopenedChest.uuid);
                commit('ADD_TO_OPENED_CHEST_RESULTS', openedChestResult);
                commit('INCREASE_SQUAD_GOLD', openedChestResult.gold);
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {text: 'Oops, something went wrong'})
            }
        },

        async stashItem({state, commit, dispatch}, item) {
            try {
                let response = await squadApi.stashItem(state.squad.slug, item.uuid);
                helpers.handleItemTransactions({state, commit, dispatch}, [new Item(response.data)]);
                dispatch('snackBarSuccess', {
                    text: item.name + ' stashed',
                    timeout: 3000
                });
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {text: 'Oops, something went wrong'})
            }
        },

        async mobileStoreItem({state, commit, dispatch}, item) {
            try {
                let response = await squadApi.mobileStoreItem(state.squad.slug, item.uuid);
                let items = response.data.map(function (itemData) {
                    return new Item(itemData);
                });
                helpers.handleItemTransactions({state, commit, dispatch}, items);
                let mobileStorageRankName = state.mobileStorage.mobileStorageRank.name;
                dispatch('snackBarSuccess', {
                    text: item.name + ' moved to ' + mobileStorageRankName,
                    timeout: 3000
                });
            } catch (e) {
                console.log(e);
                dispatch('snackBarError', {text: 'Oops, something went wrong'})
            }
        }
    },
};
