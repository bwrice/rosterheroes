import * as squadApi from '../../api/squadApi';
import * as campaignApi from '../../api/campaignApi';
import SquadSnapshot from "../../models/SquadSnapshot";
import HistoricCampaignStop from "../../models/HistoricCampaignStop";

export default {

    state: {
        focusedCampaign: null,
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
        async updateFocusedCampaign({commit, dispatch}, {focusedCampaign, squadSlug}) {

            commit('SET_HISTORIC_CAMPAIGN_STOPS_LOADED', false);
            commit('SET_FOCUSED_CAMPAIGN', focusedCampaign);

            // Update squad snapshot
            let snapshotResponse = await squadApi.getSquadSnapshot(squadSlug, focusedCampaign.weekID);
            commit('SET_SQUAD_SNAPSHOT', new SquadSnapshot(snapshotResponse.data));

            // Update campaign stops
            let campaignStopsResponse = await campaignApi.campaignStops(focusedCampaign.uuid);
            let campaignStops = campaignStopsResponse.data.map(stop => new HistoricCampaignStop(stop));
            commit('SET_HISTORIC_CAMPAIGN_STOPS', campaignStops);
            commit('SET_HISTORIC_CAMPAIGN_STOPS_LOADED', true);
        },
    }
};
