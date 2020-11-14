import HistoricCampaign from "../../models/HistoricCampaign";
import * as squadApi from '../../api/squadApi';
import * as campaignApi from '../../api/campaignApi';
import SquadSnapshot from "../../models/SquadSnapshot";
import CampaignStop from "../../models/CampaignStop";

export default {

    state: {
        focusedCampaign: new HistoricCampaign({}),
        squadSnapshot: null,
        campaignStops: [],
    },

    getters: {
        _focusedCampaign(state) {
            return state.focusedCampaign;
        },
        _squadSnapshot(state) {
            return state.squadSnapshot;
        },
        _campaignStops(state) {
            return state.campaignStops;
        }
    },

    mutations: {
        SET_FOCUSED_CAMPAIGN(state, focusedCampaign) {
            state.focusedCampaign = focusedCampaign;
        },
        SET_SQUAD_SNAPSHOT(state, squadSnapshot) {
            state.squadSnapshot = squadSnapshot;
        },
        SET_CAMPAIGN_STOPS(state, campaignStops) {
            state.campaignStops = campaignStops;
        }
    },

    actions: {
        updateFocusedCampaign({commit, dispatch}, {focusedCampaign, squadSlug}) {
            commit('SET_FOCUSED_CAMPAIGN', focusedCampaign);
            dispatch('updateSquadSnapshot', {
                squadSlug: squadSlug,
                weekID: focusedCampaign.weekID
            });
            dispatch('updateCampaignStops', {
                campaignUuid: focusedCampaign.uuid
            })
        },

        async updateSquadSnapshot({commit}, {squadSlug, weekID}) {
            let response = await squadApi.getSquadSnapshot(squadSlug, weekID);
            commit('SET_SQUAD_SNAPSHOT', new SquadSnapshot(response.data));
        },

        async updateCampaignStops({commit}, {campaignUuid}) {
            let response = await campaignApi.campaignStops(campaignUuid);
            let campaignStops = response.data.map(stop => new CampaignStop(stop))
            commit('SET_CAMPAIGN_STOPS', campaignStops);
        }
    }
};
