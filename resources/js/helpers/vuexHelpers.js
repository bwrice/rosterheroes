import Hero from "../models/Hero";
import MobileStorage from "../models/MobileStorage";

export function syncUpdatedHero(state, commit, updatedHero) {
    let heroes = _.cloneDeep(state.heroes);

    let index = heroes.findIndex(function (hero) {
        return hero.uuid === updatedHero.uuid;
    });
    if (index === -1) {
        throw new Error("Couldn't find matching Hero")
    }
    heroes.splice(index, 1, updatedHero);
    commit('SET_HEROES', heroes);
}

export function syncHasItemsResponse(state, commit, response) {
    response.data.forEach(function (hasItemsResponse) {
        if (hasItemsResponse.type === 'hero') {
            let updatedHero = new Hero(hasItemsResponse.hasItems);
            syncUpdatedHero(state, commit, updatedHero);
        }
    });
    let mobileStorageResponse = response.data.find(function (hasItemsResponse) {
        return hasItemsResponse.type === 'squad';
    });
    if (mobileStorageResponse) {
        let updateMobileStorage = new MobileStorage(mobileStorageResponse.hasItems);
        commit('SET_MOBILE_STORAGE', updateMobileStorage);
    }
}

export function handleResponseErrors(e, errorKey, dispatch) {
    let snackBarPayload = {};
    if (e.response) {
        let errors = e.response.data.errors;
        if (errors && errors[errorKey]) {
            snackBarPayload = {
                text: errors[errorKey][0]
            }
        }
    }
    dispatch('snackBarError', snackBarPayload)
}
