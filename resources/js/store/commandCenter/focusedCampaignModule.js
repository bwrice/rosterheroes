import HistoricCampaign from "../../models/HistoricCampaign";

export default {

    state: {
        focusedCampaign: new HistoricCampaign({})
    },

    getters: {
        _focusedCampaign(state) {
            return state.focusedCampaign;
        }
    },

    mutations: {
        SET_FOCUSED_CAMPAIGN(state, focusedCampaign) {
            state.focusedCampaign = focusedCampaign;
        }
    },

    actions: {
        
    }
};
