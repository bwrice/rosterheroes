import CombatGroup from "../../models/CombatGroup";

export default {

    state: {
        allyTotalInitialHealth: 0,
        enemyTotalInitialHealth: 0,
        allyHealthPercents: {
            'front-line': 0,
            'back-line': 0,
            'high-ground': 0
        },
        enemyHealthPercents: {
            'front-line': 0,
            'back-line': 0,
            'high-ground': 0
        },
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
        },
    },

    getters: {
        _allyHealthPercents(state) {
            return state.allyHealthPercents;
        },
        _enemyHealthPercents(state) {
            return state.enemyHealthPercents;
        },
        _allyDamages(state) {
            return state.allyDamages;
        },
        _enemyDamages(state) {
            return state.enemyDamages;
        },
        _allyBlocks(state) {
            return state.allyBlocks;
        },
        _enemyBlocks(state) {
            return state.enemyBlocks;
        }
    },
    mutations: {
        SET_ALLY_HEALTH_PERCENTS(state, allyHealthPercents) {
            state.allyHealthPercents = allyHealthPercents;
        },
        SET_ENEMY_HEALTH_PERCENTS(state, enemyHealthPercents) {
            state.enemyHealthPercents = enemyHealthPercents;
        },
        SET_ALLY_TOTAL_INITIAL_HEALTH(state, allyTotalInitialHealth) {
            state.allyTotalInitialHealth = allyTotalInitialHealth;
        },
        SET_ENEMY_TOTAL_INITIAL_HEALTH(state, enemyTotalInitialHealth) {
            state.enemyTotalInitialHealth = enemyTotalInitialHealth;
        },
        SET_ALLY_DAMAGES(state, allyDamages) {
            state.allyDamages = allyDamages;
        },
        SET_ENEMY_DAMAGES(state, enemyDamages) {
            state.enemyDamages = enemyDamages;
        },
        SET_ALLY_BLOCKS(state, allyBlocks) {
            state.allyBlocks = allyBlocks;
        },
        SET_ENEMY_BLOCKS(state, enemyBlocks) {
            state.enemyBlocks = enemyBlocks;
        },
    },

    actions: {

    }
};
