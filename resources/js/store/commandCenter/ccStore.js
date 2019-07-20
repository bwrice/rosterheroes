import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squad';
import rosterModule from './roster';

export const store = new Vuex.Store({

    modules: {
        squadModule,
        rosterModule
    }
});