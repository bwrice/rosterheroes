
const EMPTY_COMBAT_POSITION_OBJECT = {
        'front-line': [],
        'back-line': [],
        'high-ground': []
    };

const MAX_SPEED = 250;

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
        allyDamages: EMPTY_COMBAT_POSITION_OBJECT,
        enemyDamages: EMPTY_COMBAT_POSITION_OBJECT,
        allyBlocks: EMPTY_COMBAT_POSITION_OBJECT,
        enemyBlocks: EMPTY_COMBAT_POSITION_OBJECT,
        battlefieldSpeed: 1000
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
        },
        _battlefieldSpeed(state) {
            return state.battlefieldSpeed;
        },
        _battlefieldSpeedMaxed(state) {
            return state.battlefieldSpeed <= MAX_SPEED;
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
        CLEAR_ALLY_DAMAGES(state) {
            state.allyDamages = EMPTY_COMBAT_POSITION_OBJECT;
        },
        CLEAR_ENEMY_DAMAGES(state) {
            state.enemyDamages = EMPTY_COMBAT_POSITION_OBJECT;
        },
        CLEAR_ALLY_BLOCKS(state) {
            state.allyBlocks = EMPTY_COMBAT_POSITION_OBJECT;
        },
        CLEAR_ENEMY_BLOCKS(state) {
            state.enemyBlocks = EMPTY_COMBAT_POSITION_OBJECT;
        },
        INCREASE_BATTLEFIELD_SPEED(state) {
            let currentSpeed = state.battlefieldSpeed;
            let newSpeed = 1000;
            switch (currentSpeed) {
                case 2000:
                    newSpeed = 1400;
                    break;
                case 1400:
                    newSpeed = 1000;
                    break;
                case 1000:
                    newSpeed = 800;
                    break;
                case 800:
                    newSpeed = 500;
                    break;
                case 500:
                    newSpeed = 400;
                    break;
                case 400:
                    newSpeed = 320;
                    break;
                default:
                    newSpeed = MAX_SPEED;
                    break;
            }
            state.battlefieldSpeed = newSpeed;
        },
    },

    actions: {
        increaseBattlefieldSpeed({commit}) {
            commit('INCREASE_BATTLEFIELD_SPEED');
        }
    }
};
