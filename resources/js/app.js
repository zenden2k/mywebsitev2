require('./bootstrap');
require('admin-lte');

import Vue from "vue/dist/vue.js";
import VueRouter from "vue-router";
Vue.use(VueRouter)

import Vue2Editor from "vue2-editor";
Vue.use(Vue2Editor);

Vue.config.productionTip = false

//register component
Vue.component('pagination', require('laravel-vue-pagination'));


Vue.filter('truncate', function (value, size) {
    if (!value) return '';
    value = value.toString();

    if (value.length <= size) {
        return value;
    }
    return value.substr(0, size) + '...';
});

import moment from 'moment'

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY HH:mm')
    }
});

import router from './router'

const app = new Vue({
    router,
    //mode: "history"
}).$mount('#app')
//app.config.globalProperties.$axios = axios;
