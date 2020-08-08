import * as squadApi from '../../api/squadApi';
import * as helpers from '../../helpers/vuexHelpers';
import RecruitmentCamp from "../../models/RecruitmentCamp";

export default {

    state: {
        recruitmentCamp: new RecruitmentCamp({}),
        recruitmentHeroRace: null,
        recruitmentHeroPostType: null,
        recruitmentHeroClass: null,
        serverNameErrors: []
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
        },
        _recruitmentServerNameErrors(state) {
            return state.serverNameErrors;
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
        SET_SERVER_NAME_ERRORS(state, nameErrors) {
            state.serverNameErrors = nameErrors;
        },
        CLEAR_SERVER_NAME_ERRORS(state) {
            state.serverNameErrors = [];
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
        },
        async recruitHero({commit, state, dispatch}, {route, heroName}) {
            try {
                let response = await squadApi.recruitHero(route.params.squadSlug, route.params.recruitmentCampSlug, {
                    heroPostTypeID: state.recruitmentHeroPostType.id,
                    heroRaceID: state.recruitmentHeroRace.id,
                    heroClassID: state.recruitmentHeroClass.id,
                    heroName: heroName
                });

                console.log(response.data);

            } catch (e) {
                if (e.response) {
                    let errors = e.response.data.errors;
                    if (errors['heroName']) {
                        commit('SET_SERVER_NAME_ERRORS', errors['heroName']);
                        helpers.handleResponseErrors(e, 'heroName', dispatch);
                    } else {
                        helpers.handleResponseErrors(e, 'recruit', dispatch);
                    }
                }
            }
        },
        clearRecruitmentServerNameErrors({commit}) {
            commit('CLEAR_SERVER_NAME_ERRORS');
        }
    }
};
