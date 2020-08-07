import * as squadApi from '../../api/squadApi';
import RecruitmentCamp from "../../models/RecruitmentCamp";

export default {

    state: {
        recruitmentCamp: new RecruitmentCamp({}),
        recruitmentHeroRace: null,
        recruitmentHeroPostType: null,
        recruitmentHeroClass: null,
    },

    getters: {
        _recruitmentCamp(state) {
            return state.recruitmentCamp;
        },
        _recruitmentHeroRace(state) {
            return state.recruitmentHeroRace;
        },
        _recruitmentHeroPostType(state) {
            return state.recruitmentHeroPostType;
        },
        _recruitmentHeroClass(state) {
            return state.recruitmentHeroClass;
        }
    },
    mutations: {
        SET_RECRUITMENT_CAMP(state, recruitmentCamp) {
            state.recruitmentCamp = recruitmentCamp;
        },
        SET_RECRUITMENT_HERO_RACE(state, heroRace) {
            state.recruitmentHeroRace = heroRace;
        },
        SET_RECRUITMENT_HERO_CLASS(state, heroClass) {
            state.recruitmentHeroClass = heroClass;
        },
        SET_RECRUITMENT_HERO_POST_TYPE(state, heroPostType) {
            state.recruitmentHeroPostType = heroPostType;
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
        setRecruitmentHeroRace({commit}, heroRace) {
            commit('SET_RECRUITMENT_HERO_RACE', heroRace);
        },
        setRecruitmentHeroClass({commit}, heroClass) {
            commit('SET_RECRUITMENT_HERO_CLASS', heroClass);
        },
        setRecruitmentHeroPostType({commit}, heroPostType) {
            commit('SET_RECRUITMENT_HERO_POST_TYPE', heroPostType);
        }
    }
};
