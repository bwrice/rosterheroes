import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squadModule';
import heroModule from './heroModule';
import rosterModule from './rosterModule';
import barracksModule from "./barracksModule";
import weekModule from './weekModule';
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
        barracksModule,
        weekModule,
        exploreModule,
        currentLocationModule,
        travelModule,
        snackBarModule,
        overlayModule
    }
});
