import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squad';

export const store = new Vuex.Store({

    modules: {
        squadModule
    }
});