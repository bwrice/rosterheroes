import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import rosterModule from './rosterModule';
import squadModule from "./squadModule";
import weekModule from './weekModule';
import realmModule from './realmModule';
import snackBarModule from './snackBarModule';
import overlayModule from "./overlayModule";
import currentLocationModule from "./currentLocationModule";
import travelModule from "./travelModule";
import referenceModule from "./referenceModule";

export const store = new Vuex.Store({

    modules: {
        rosterModule,
        squadModule,
        weekModule,
        realmModule,
        currentLocationModule,
        travelModule,
        snackBarModule,
        overlayModule,
        referenceModule
    }
});
