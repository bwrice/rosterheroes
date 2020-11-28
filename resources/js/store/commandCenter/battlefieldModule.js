
export default {

    state: {
        allyDamages: {
            'front-line': [],
            'back-line': [],
            'high-ground': []
        },
        enemyDamages: {
            'front-line': [],
            'back-line': [],
            'high-ground': []
        },
        allyBlocks: {
            'front-line': [],
            'back-line': [],
            'high-ground': []
        },
        enemyBlocks: {
            'front-line': [],
            'back-line': [],
            'high-ground': []
        }
    },

    getters: {
        _allyDamages(state) {
            return state.allyDamages;
        },
        _enemyDamages(state) {
            return state.enemyDamages;
        },
        _allyBlocks(state) {
            return state.allyDamages;
        },
        _enemyBlocks(state) {
            return state.enemyDamages;
        }
    },
    mutations: {

    },

    actions: {

    }
};
