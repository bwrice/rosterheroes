require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import { routes } from './routes/ccRoutes';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});


import Vuex from 'vuex';

Vue.use(Vuex);

import CommandCenter from './views/CommandCenter';
import { store } from "./store/commandCenter/ccStore";

const app = new Vue({
    el: '#app',
    store,
    router,
    components: {
        CommandCenter
    }
});