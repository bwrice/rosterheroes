import HistoricCampaign from "../../models/HistoricCampaign";
import * as squadApi from '../../api/squadApi';

export default {

    state: {
        focusedCampaign: new HistoricCampaign({}),
        squadSnapshot: null
    },

    getters: {
        _focusedCampaign(state) {
            return state.focusedCampaign;
        },
        _squadSnapshot(state) {
            return state.squadSnapshot;
        }
    },

    mutations: {
        SET_FOCUSED_CAMPAIGN(state, focusedCampaign) {
            state.focusedCampaign = focusedCampaign;
        },
        SET_SQUAD_SNAPSHOT(state, squadSnapshot) {
            state.squadSnapshot = squadSnapshot;
        }
    },

    actions: {
        updateFocusedCampaign({commit, dispatch}, {focusedCampaign, squadSlug}) {
            commit('SET_FOCUSED_CAMPAIGN', focusedCampaign);
            dispatch('updateSquadSnapshot', {
                squadSlug: squadSlug,
                weekID: focusedCampaign.weekID
            })
        },

        async updateSquadSnapshot({commit}, {squadSlug, weekID}) {
            let response = await squadApi.getSquadSnapshot(squadSlug, weekID);
            commit('SET_SQUAD_SNAPSHOT', response.data);
        }
    }
};
