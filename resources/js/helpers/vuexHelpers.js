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
    // TODO local Stash and Residence
}

export function handleItemTransactions({state, commit, dispatch}, items) {
    let heroUuidsToUpdate = [];
    items.forEach(function (item) {
        let transaction = item.transaction;
        switch(transaction.to.type) {
            case 'heroes':
                heroUuidsToUpdate.push(transaction.to.uuid);
                break;
            case 'squads':
                commit('ADD_ITEM_TO_MOBILE_STORAGE', item);
                break;
            case 'stashes':
                commit('ADD_ITEM_TO_LOCAL_STASH', item);
                break;
            // TODO: residence
        }
        switch(transaction.from.type) {
            case 'heroes':
                heroUuidsToUpdate.push(transaction.from.uuid);
                console.log("Adding hero to update: " + transaction.from.uuid);
                break;
            case 'squads':
                commit('REMOVE_ITEM_FROM_MOBILE_STORAGE', item);
                break;
            case 'stashes':
                commit('REMOVE_ITEM_FROM_LOCAL_STASH', item);
                break;
            // // TODO: residence
        }
    });
    _.uniq(heroUuidsToUpdate).forEach(function (heroUuid) {

        let heroToUpdate = state.heroes.find(function (hero) {
            return hero.uuid === heroUuid;
        });
        if (heroToUpdate) {
            dispatch('updateHero', heroToUpdate.slug);
        } else {
            console.warn("Couldn't find hero to update with uuid: " + heroUuid);
        }
    })
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
