import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squad';
import rosterModule from './roster';
import weekModule from './week';

export const store = new Vuex.Store({

    modules: {
        squadModule,
        rosterModule,
        weekModule
    }
});