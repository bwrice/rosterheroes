require('./bootstrap');

import Vue from 'vue';

import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'; // TODO: remove this if we want to override sass variables
import '@mdi/font/css/materialdesignicons.css';
Vue.use(Vuetify);

import { vuetifyOptions } from "./vuetifyOptions";

import CreateSquad from './views/CreateSquad';

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(vuetifyOptions),
    components: {
        CreateSquad
    }
});