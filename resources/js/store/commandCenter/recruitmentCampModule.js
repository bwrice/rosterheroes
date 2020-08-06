import * as squadApi from '../../api/squadApi';
import RecruitmentCamp from "../../models/RecruitmentCamp";

export default {

    state: {
        recruitmentCamp: new RecruitmentCamp({}),
    },

    getters: {
        _recruitmentCamp(state) {
            return state.recruitmentCamp;
        }
    },
    mutations: {
        SET_RECRUITMENT_CAMP(state, recruitmentCamp) {
            state.recruitmentCamp = recruitmentCamp;
        },
    },
    actions: {
        async updateRecruitmentCamp({commit}, route) {
            try {
                let response = await squadApi.getRecruitmentCamp(route.params.squadSlug, route.params.recruitmentCampSlug);
                let recruitmentCamp = new RecruitmentCamp(response.data);
                commit('SET_RECRUITMENT_CAMP', recruitmentCamp)
            } catch (e) {
                console.warn("Failed to update shop");
            }
        },
    }
};
