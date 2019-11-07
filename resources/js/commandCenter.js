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
import 'vuetify/dist/vuetify.min.css'; // TODO: remove this if we want to override sass variables
import '@mdi/font/css/materialdesignicons.css';

Vue.use(Vuetify);

import Vuex from 'vuex';
Vue.use(Vuex);

import VDragged from "v-dragged";
Vue.use(VDragged);

import { store } from "./store/commandCenter/ccStore";

import { vuetifyOptions } from "./vuetifyOptions";

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(vuetifyOptions),
    store,
    router
});
