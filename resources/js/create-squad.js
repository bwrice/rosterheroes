require('./bootstrap');


import Vue from 'vue';
import Vuetify from 'vuetify';

Vue.use(Vuetify, {
    theme: {
        primary: '#419183'
    }
});

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import CreateSquad from './views/CreateSquad';

const app = new Vue({
    el: '#app',
    components: {
        CreateSquad
    }
});