require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import { routes } from './routes/ccRoutes';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes,
    // always go to top of content when new route is hit
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify);

import Vuex from 'vuex';
Vue.use(Vuex);

import CommandCenter from './views/CommandCenter';
import { store } from "./store/commandCenter/ccStore";

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
    }
};

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(vuetifyOptions),
    store,
    router,
    components: {
        CommandCenter
    }
});