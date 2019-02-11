require('./bootstrap');

import Vue from 'vue';

import CreateSquad from './views/CreateSquad';

const app = new Vue({
    el: '#app',
    components: {
        CreateSquad
    }
});