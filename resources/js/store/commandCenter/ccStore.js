import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import squadModule from "./squadModule";
import weekModule from './weekModule';
import realmModule from './realmModule';
import snackBarModule from './snackBarModule';
import overlayModule from "./overlayModule";
import currentLocationModule from "./currentLocationModule";
import travelModule from "./travelModule";
import referenceModule from "./referenceModule";
import shopModule from "./shopModule";
import recruitmentCampModule from "./recruitmentCampModule";
import focusedCampaignModule from "./focusedCampaignModule";
import sideQuestReplayModule from "./sideQuestReplayModule";

export const store = new Vuex.Store({

    modules: {
        squadModule,
        weekModule,
        realmModule,
        currentLocationModule,
        travelModule,
        snackBarModule,
        overlayModule,
        referenceModule,
        shopModule,
        recruitmentCampModule,
        focusedCampaignModule,
        sideQuestReplayModule
    }
});
