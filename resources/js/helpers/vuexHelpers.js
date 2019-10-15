
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
