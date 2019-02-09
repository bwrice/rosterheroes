require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Vuex from 'vuex';

Vue.use(Vuex);

import CommandCenter from './views/CommandCenter';

const app = new Vue({
    el: '#app',
    components: {
        CommandCenter
    }
});