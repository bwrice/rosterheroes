
const MAX_SPEED = 250;
const DEFAULT_BATTLEFIELD_SPEED = 1000;

const BLANK_HEALTH_OBJECT = {
    'front-line': 0,
    'back-line': 0,
    'high-ground': 0
};

export default {

    state: {
        allyHealthPercents: BLANK_HEALTH_OBJECT,
        enemyHealthPercents: BLANK_HEALTH_OBJECT,
        battlefieldSpeed: DEFAULT_BATTLEFIELD_SPEED,
        battlefieldAttacks: []
    },

    getters: {
        _allyHealthPercents(state) {
            return state.allyHealthPercents;
        },
        _enemyHealthPercents(state) {
            return state.enemyHealthPercents;
        },
        _battlefieldSpeed(state) {
            return state.battlefieldSpeed;
        },
        _battlefieldSpeedMaxed(state) {
            return state.battlefieldSpeed <= MAX_SPEED;
        },
        _battlefieldAttacks(state) {
            return state.battlefieldAttacks;
        }
    },
    mutations: {
        SET_ALLY_HEALTH_PERCENTS(state, allyHealthPercents) {
            state.allyHealthPercents = allyHealthPercents;
        },
        SET_ENEMY_HEALTH_PERCENTS(state, enemyHealthPercents) {
            state.enemyHealthPercents = enemyHealthPercents;
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
        SET_BATTLEFIELD_ATTACKS(state, attacks) {
            state.battlefieldAttacks = attacks;
        },
        RESET_BATTLEFIELD(state) {
            state.battlefieldSpeed = DEFAULT_BATTLEFIELD_SPEED;
            state.battlefieldAttacks = [];
            state.allyHealthPercents = BLANK_HEALTH_OBJECT;
            state.enemyHealthPercents = BLANK_HEALTH_OBJECT;
        }
    },

    actions: {
        increaseBattlefieldSpeed({commit}) {
            commit('INCREASE_BATTLEFIELD_SPEED');
        },
        resetBattlefield({commit}) {
            commit('RESET_BATTLEFIELD');
        }
    }
};
