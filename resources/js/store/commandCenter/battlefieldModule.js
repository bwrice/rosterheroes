
const MAX_SPEED = 128;
const MIN_SPEED = 8192;
const DEFAULT_BATTLEFIELD_SPEED = 1024;

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
        battlefieldAttacks: [],
        battlefieldDeaths: []
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
        _battlefieldSpeedBottomed(state) {
            return state.battlefieldSpeed >= MIN_SPEED;
        },
        _battlefieldAttacks(state) {
            return state.battlefieldAttacks;
        },
        _battlefieldDeaths(state) {
            return state.battlefieldDeaths;
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
            let newSpeed = currentSpeed / 2;
            if (newSpeed <= MAX_SPEED) {
                newSpeed = MAX_SPEED;
            }
            state.battlefieldSpeed = newSpeed;
        },
        DECREASE_BATTLEFIELD_SPEED(state) {
            let currentSpeed = state.battlefieldSpeed;
            let newSpeed = currentSpeed * 2;
            if (newSpeed >= MIN_SPEED) {
                newSpeed = MIN_SPEED;
            }
            state.battlefieldSpeed = newSpeed;
        },
        SET_BATTLEFIELD_ATTACKS(state, attacks) {
            state.battlefieldAttacks = attacks;
        },
        PUSH_BATTLEFIELD_DEATHS(state, deaths) {
            deaths.forEach(death => state.battlefieldDeaths.push(death));
        },
        RESET_BATTLEFIELD(state) {
            state.battlefieldSpeed = DEFAULT_BATTLEFIELD_SPEED;
            state.battlefieldAttacks = [];
            state.battlefieldDeaths = [];
            state.allyHealthPercents = BLANK_HEALTH_OBJECT;
            state.enemyHealthPercents = BLANK_HEALTH_OBJECT;
        }
    },

    actions: {
        increaseBattlefieldSpeed({commit}) {
            commit('INCREASE_BATTLEFIELD_SPEED');
        },
        decreaseBattlefieldSpeed({commit}) {
            commit('DECREASE_BATTLEFIELD_SPEED');
        },
        resetBattlefield({commit}) {
            commit('RESET_BATTLEFIELD');
        }
    }
};
