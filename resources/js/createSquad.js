require('./bootstrap');

import Vue from 'vue';

import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'; // TODO: remove this if we want to override sass variables
import '@mdi/font/css/materialdesignicons.css';
Vue.use(Vuetify);

const vuetifyOptions = {
    theme: {
        dark: true,
        themes: {
            dark: {
                primary: '#3fa391',
                accent: '#ffc747',
                info: '#6a6099',
                success: '#52b266'
            }
        }
    },
    icons: {
        iconfont: 'mdi'
    },
};


import CreateSquad from './views/CreateSquad';

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(vuetifyOptions),
    components: {
        CreateSquad
    }
});