require('./bootstrap');


import Vue from 'vue';
import Vuetify from 'vuetify';

Vue.use(Vuetify, {
    theme: {
        primary: '#BFAC73'
    }
});

import CreateSquad from './views/CreateSquad';

const app = new Vue({
    el: '#app',
    components: {
        CreateSquad
    }
});