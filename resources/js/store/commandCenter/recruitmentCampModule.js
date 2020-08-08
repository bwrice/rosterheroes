import * as squadApi from '../../api/squadApi';
import * as helpers from '../../helpers/vuexHelpers';
import RecruitmentCamp from "../../models/RecruitmentCamp";
import Hero from "../../models/Hero";

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
        CLEAR_RECRUITMENT_SELECTIONS(state) {
            state.recruitmentHeroRace = null;
            state.recruitmentHeroPostType = null;
            state.recruitmentHeroClass = null;
        }
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
                let heroResponse = await squadApi.recruitHero(route.params.squadSlug, route.params.recruitmentCampSlug, {
                    heroPostTypeID: state.recruitmentHeroPostType.id,
                    heroRaceID: state.recruitmentHeroRace.id,
                    heroClassID: state.recruitmentHeroClass.id,
                    heroName: heroName
                });

                commit('DECREASE_SQUAD_GOLD', state.recruitmentHeroPostType.recruitmentCost);
                commit('INCREASE_SQUAD_ESSENCE', state.recruitmentHeroPostType.recruitmentBonusSpiritEssence);

                let recruitedHero = new Hero(heroResponse.data);
                commit('REPLACE_UPDATED_HERO', recruitedHero);

                // Have to update the recruitment camp for new cost and bonus essence amounts
                let campResponse = await squadApi.getRecruitmentCamp(route.params.squadSlug, route.params.recruitmentCampSlug);
                let recruitmentCamp = new RecruitmentCamp(campResponse.data);
                commit('SET_RECRUITMENT_CAMP', recruitmentCamp);

                dispatch('snackBarSuccess', {
                    text: heroName + ' recruited!',
                    timeout: 3000
                });

                commit('CLEAR_RECRUITMENT_SELECTIONS');

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
