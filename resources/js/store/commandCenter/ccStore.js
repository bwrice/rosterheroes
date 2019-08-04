import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squad';
import heroModule from './heroModule';
import rosterModule from './roster';
import weekModule from './week';
import mapModule from './mapModule';
import snackBarModule from './snackBar';
import provinceModule from "./provinceModule";

export const store = new Vuex.Store({

    modules: {
        squadModule,
        heroModule,
        rosterModule,
        weekModule,
        mapModule,
        provinceModule,
        snackBarModule
    }
});
