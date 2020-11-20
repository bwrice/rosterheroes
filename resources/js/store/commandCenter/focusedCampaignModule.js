import HistoricCampaign from "../../models/HistoricCampaign";
import * as squadApi from '../../api/squadApi';
import * as campaignApi from '../../api/campaignApi';
import SquadSnapshot from "../../models/SquadSnapshot";
import HistoricCampaignStop from "../../models/HistoricCampaignStop";

export default {

    state: {
        focusedCampaign: new HistoricCampaign({}),
        squadSnapshot: null,
        historicCampaignStops: [],
        historicCampaignStopsLoaded: false
    },

    getters: {
        _focusedCampaign(state) {
            return state.focusedCampaign;
        },
        _squadSnapshot(state) {
            return state.squadSnapshot;
        },
        _historicCampaignStops(state) {
            return state.historicCampaignStops;
        },
        _historicCampaignStopsLoaded(state) {
            return state.historicCampaignStopsLoaded;
        }
    },

    mutations: {
        SET_FOCUSED_CAMPAIGN(state, focusedCampaign) {
            state.focusedCampaign = focusedCampaign;
        },
        SET_SQUAD_SNAPSHOT(state, squadSnapshot) {
            state.squadSnapshot = squadSnapshot;
        },
        SET_HISTORIC_CAMPAIGN_STOPS(state, campaignStops) {
            state.historicCampaignStops = campaignStops;
        },
        SET_HISTORIC_CAMPAIGN_STOPS_LOADED(state, loaded) {
            state.historicCampaignStopsLoaded = loaded;
        }
    },

    actions: {
        updateFocusedCampaign({commit, dispatch}, {focusedCampaign, squadSlug, route}) {
            commit('SET_FOCUSED_CAMPAIGN', focusedCampaign);
            dispatch('updateSquadSnapshot', {
                squadSlug: squadSlug,
                weekID: focusedCampaign.weekID
            });
            dispatch('updateHistoricCampaignStops', {
                campaignUuid: focusedCampaign.uuid,
                route: route
            });
        },

        async updateSquadSnapshot({commit}, {squadSlug, weekID}) {
            let response = await squadApi.getSquadSnapshot(squadSlug, weekID);
            commit('SET_SQUAD_SNAPSHOT', new SquadSnapshot(response.data));
        },

        async updateHistoricCampaignStops({commit, dispatch}, {campaignUuid, route}) {
            commit('SET_HISTORIC_CAMPAIGN_STOPS_LOADED', false);
            let response = await campaignApi.campaignStops(campaignUuid);
            let campaignStops = response.data.map(stop => new HistoricCampaignStop(stop));
            commit('SET_HISTORIC_CAMPAIGN_STOPS', campaignStops);
            commit('SET_HISTORIC_CAMPAIGN_STOPS_LOADED', true);

            if (route.params.sideQuestResultUuid) {
                let sqResults = [];
                campaignStops.forEach(campaignStop => sqResults.push(...campaignStop.sideQuestResults));
                let sideQuestResult = sqResults.find(result => result.uuid === route.params.sideQuestResultUuid);
                dispatch('setupSideQuestReplay', {sideQuestResult});
            }
        }
    }
};
