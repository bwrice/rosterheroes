import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from './squadModule';
import heroModule from './heroModule';
import rosterModule from './rosterModule';
import barracksModule from "./barracksModule";
import weekModule from './weekModule';
import realmModule from './realmModule';
import snackBarModule from './snackBarModule';
import overlayModule from "./overlayModule";
import currentLocationModule from "./currentLocationModule";
import travelModule from "./travelModule";
import referenceDataModule from "./referenceDataModule";

export const store = new Vuex.Store({

    modules: {
        squadModule,
        heroModule,
        rosterModule,
        barracksModule,
        weekModule,
        realmModule,
        currentLocationModule,
        travelModule,
        snackBarModule,
        overlayModule,
        referenceDataModule
    }
});
