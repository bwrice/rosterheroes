
const MAX_SPEED = 250;

export default {

    state: {
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
        battlefieldSpeed: 1000,
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
    },

    actions: {
        increaseBattlefieldSpeed({commit}) {
            commit('INCREASE_BATTLEFIELD_SPEED');
        }
    }
};
