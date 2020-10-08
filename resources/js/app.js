/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate';

import axios from "axios";
import VueAxios from 'vue-axios'

Vue.use(VueRouter);
Vue.use(Vuelidate);
Vue.use(VueAxios, axios)

Vue.prototype.$axios = axios;

import {routes} from './routes'
import {store} from './store/Store'


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('app-component', require('./components/App.vue').default);

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const router= new VueRouter({
    routes,
    mode: 'history',
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app= new Vue({
    el: '#app',
    store,
    router
})

// Vue.component('app-component', require('./App.vue').default);
