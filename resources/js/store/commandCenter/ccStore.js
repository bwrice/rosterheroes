import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squad';
import heroModule from './heroModule';
import rosterModule from './roster';
import weekModule from './week';
import exploreModule from './exploreModule';
import snackBarModule from './snackBar';
import overlayModule from "./overlayModule";
import currentLocationModule from "./currentLocationModule";
import travelModule from "./travelModule";

export const store = new Vuex.Store({

    modules: {
        squadModule,
        heroModule,
        rosterModule,
        weekModule,
        exploreModule,
        currentLocationModule,
        travelModule,
        snackBarModule,
        overlayModule
    }
});
